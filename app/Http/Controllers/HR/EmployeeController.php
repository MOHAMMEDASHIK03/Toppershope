<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use Illuminate\Http\Request;
use App\Models\HR\Employee;
use App\Models\HR\Department;
use App\Models\HR\Designation;
use App\Models\HR\HrUser;
use App\Models\User;
use App\Models\Ads\AdsUser;
use App\Models\Admission\AdmissionUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Branch;
use App\Services\HR\EmployeeIdGenerator;
use App\Traits\LogsAdminActivity;

class EmployeeController extends Controller
{
    use LogsAdminActivity, PaginatesListings;

    public function index(Request $request)
    {
        $query = Employee::with(['department', 'designation', 'account']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->has('export')) {
            return $this->export($query, $request->export);
        }

        $employees = $this->paginateListing($query->latest(), $request);
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('hr.employees.index', compact('employees', 'departments'));
    }

    private function export($query, $format)
    {
        $employees = $query->get();

        if ($format === 'csv') {
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=employees_" . date('Ymd_His') . ".csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use ($employees) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['EMP ID', 'Name', 'Email', 'Department', 'Designation', 'Joined', 'Status', 'Panel Access']);

                foreach ($employees as $emp) {
                    $panel = 'None';
                    if ($emp->account) {
                        $panel = class_basename($emp->account_type);
                        if ($panel === 'User') {
                            $panel = isset($emp->account->role) && $emp->account->role === 'faculty_head' ? 'Faculty Head' : 'Faculty';
                        } elseif ($panel === 'HrUser') {
                            $panel = 'HR Panel';
                        } elseif ($panel === 'AdsUser') {
                            $panel = 'Ads Panel';
                        } elseif ($panel === 'AdmissionUser') {
                            $panel = 'Admission CRM';
                        }
                    }

                    fputcsv($file, [
                        $emp->employee_id,
                        trim($emp->first_name . ' ' . $emp->last_name),
                        $emp->email,
                        $emp->department->name ?? '-',
                        $emp->designation->name ?? '-',
                        $emp->joining_date ? $emp->joining_date->format('d M, Y') : '-',
                        $emp->is_active ? 'Active' : 'Inactive',
                        $panel
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Invalid export format.');
    }

    /**
     * Sync all existing panel users into the employees table as employee records.
     * This creates an Employee profile for every panel user that doesn't have one yet.
     */
    public function syncFromPanels(Request $request)
    {
        $idGenerator = app(EmployeeIdGenerator::class);
        $created = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            $defaultBranch = Branch::first();
            if (!$defaultBranch) {
                return back()->with('error', 'Sync failed: Please create at least one Branch in the system before syncing.');
            }

            // 1) HR Users
            foreach (HrUser::where('is_active', true)->get() as $user) {
                if (Employee::where('email', $user->email)->exists()) { $skipped++; continue; }
                $name = explode(' ', $user->name, 2);
                Employee::create([
                    'employee_id' => $idGenerator->next($defaultBranch->id, $created),
                    'first_name'  => $name[0],
                    'last_name'   => $name[1] ?? '',
                    'email'       => $user->email,
                    'is_active'   => true,
                    'account_type' => HrUser::class,
                    'account_id'   => $user->id,
                    'branch_id'    => $defaultBranch->id,
                    'office_branch' => $defaultBranch->name,
                ]);
                $created++;
            }

            // 2) Faculty & Admin Users (users table — role: faculty, admin)
            foreach (User::whereIn('role', ['faculty', 'admin', 'faculty_head'])->get() as $user) {
                if (Employee::where('email', $user->email)->exists()) { $skipped++; continue; }
                $name = explode(' ', $user->name, 2);
                Employee::create([
                    'employee_id'  => $idGenerator->next($defaultBranch->id, $created),
                    'first_name'   => $name[0],
                    'last_name'    => $name[1] ?? '',
                    'email'        => $user->email,
                    'is_active'    => true,
                    'account_type' => User::class,
                    'account_id'   => $user->id,
                    'branch_id'    => $defaultBranch->id,
                    'office_branch' => $defaultBranch->name,
                ]);
                $created++;
            }

            // 3) Ads Users
            foreach (AdsUser::where('is_active', true)->get() as $user) {
                if (Employee::where('email', $user->email)->exists()) { $skipped++; continue; }
                $name = explode(' ', $user->name, 2);
                Employee::create([
                    'employee_id'  => $idGenerator->next($defaultBranch->id, $created),
                    'first_name'   => $name[0],
                    'last_name'    => $name[1] ?? '',
                    'email'        => $user->email,
                    'is_active'    => true,
                    'account_type' => AdsUser::class,
                    'account_id'   => $user->id,
                    'branch_id'    => $defaultBranch->id,
                    'office_branch' => $defaultBranch->name,
                ]);
                $created++;
            }

            // 4) Admission Users
            foreach (AdmissionUser::where('is_active', true)->get() as $user) {
                if (Employee::where('email', $user->email)->exists()) { $skipped++; continue; }
                $name = explode(' ', $user->name, 2);
                Employee::create([
                    'employee_id'  => $idGenerator->next($defaultBranch->id, $created),
                    'first_name'   => $name[0],
                    'last_name'    => $name[1] ?? '',
                    'email'        => $user->email,
                    'is_active'    => true,
                    'account_type' => AdmissionUser::class,
                    'account_id'   => $user->id,
                    'branch_id'    => $defaultBranch->id,
                    'office_branch' => $defaultBranch->name,
                ]);
                $created++;
            }

            DB::commit();
            $this->logAudit('sync_employees_from_panels', "Synced employees from panels. Created: {$created}, Skipped: {$skipped}.");
            return back()->with('success', "Sync complete: {$created} employees imported, {$skipped} already existed.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Sync failed: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $departments = Department::where('is_active', true)
            ->with(['designations' => fn ($q) => $q->where('is_active', true)->orderBy('name')])
            ->orderBy('name')
            ->get();
        $managers = Employee::where('is_active', true)->orderBy('first_name')->get();
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        return view('hr.employees.create', compact('departments', 'managers', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:employees,email',
            'department_id' => 'required|exists:departments,id',
            'designation_id'=> 'required|exists:designations,id',
            'joining_date'  => 'required|date',
            'branch_id'     => 'required|exists:branches,id',
            'panel_access'  => 'nullable|in:none,hr,faculty,faculty_head,ads,admission',
            'panel_password' => 'required_if:panel_access,hr,faculty,faculty_head,ads,admission|nullable|string|min:6',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Handle Photo
            $photoPath = null;
            if ($request->hasFile('profile_photo')) {
                $photoPath = $request->file('profile_photo')->store('employees/photos', 'public');
            }

            $empId = app(EmployeeIdGenerator::class)->next($request->integer('branch_id'));

            $accountType = null;
            $accountId   = null;

            $panel  = $request->panel_access;
            $name   = trim($request->first_name . ' ' . $request->last_name);
            $password = $panel && $panel !== 'none' ? Hash::make($request->panel_password) : null;

            if ($panel && $panel !== 'none') {
                $tableName = match($panel) {
                    'hr'        => 'hr_users',
                    'faculty', 'faculty_head' => 'users',
                    'ads'       => 'ads_users',
                    'admission' => 'admission_users',
                };
                if (DB::table($tableName)->where('email', $request->email)->exists()) {
                    return back()->withInput()->with('error', "An account with this email already exists in the {$panel} panel.");
                }

                if ($panel === 'hr') {
                    $user = HrUser::create([
                        'name' => $name,
                        'email' => $request->email,
                        'password' => $password,
                        'role' => 'hr_executive',
                        'is_active' => true,
                    ]);
                    $accountType = HrUser::class; $accountId = $user->id;
                } elseif ($panel === 'faculty') {
                    $user = User::create(['name' => $name, 'email' => $request->email, 'password' => $password, 'role' => 'faculty']);
                    $accountType = User::class; $accountId = $user->id;
                } elseif ($panel === 'faculty_head') {
                    $user = User::create(['name' => $name, 'email' => $request->email, 'password' => $password, 'role' => 'faculty_head']);
                    $accountType = User::class; $accountId = $user->id;
                } elseif ($panel === 'ads') {
                    $user = AdsUser::create(['name' => $name, 'email' => $request->email, 'password' => $password, 'role' => 'ads_manager', 'is_active' => true]);
                    $accountType = AdsUser::class; $accountId = $user->id;
                } elseif ($panel === 'admission') {
                    $user = AdmissionUser::create(['name' => $name, 'email' => $request->email, 'password' => $password, 'role' => 'member', 'is_active' => true]);
                    $accountType = AdmissionUser::class; $accountId = $user->id;
                }
            }

            // Create Employee Core Record
            $employee = Employee::create([
                'employee_id'    => $empId,
                'first_name'     => $request->first_name,
                'middle_name'    => $request->middle_name,
                'last_name'      => $request->last_name,
                'email'          => $request->email,
                'official_email' => $request->official_email,
                'phone'          => $request->phone,
                'department_id'  => $request->department_id,
                'designation_id' => $request->designation_id,
                'joining_date'   => $request->joining_date,
                'date_of_birth'  => $request->date_of_birth,
                'employee_type'  => $request->employee_type ?? 'full_time',
                'work_location'  => $request->work_location,
                'office_branch'  => $request->branch_id ? Branch::find($request->branch_id)?->name : null,
                'branch_id'      => $request->branch_id,
                'reporting_manager_id' => $request->reporting_manager_id,
                'probation_period' => $request->probation_period,
                'confirmation_date'=> $request->confirmation_date,
                'profile_photo'  => $photoPath,
                'account_type'   => $accountType,
                'account_id'     => $accountId,
                'is_active'      => true,
            ]);

            // 1. Personal
            $employee->personal()->create([
                'gender'         => $request->gender,
                'blood_group'    => $request->blood_group,
                'nationality'    => $request->nationality,
                'marital_status' => $request->marital_status,
                'spouse_name'    => $request->spouse_name,
                'num_dependents' => $request->num_dependents ?? 0,
            ]);

            // 2. Contact
            $employee->contact()->create([
                'alternate_phone'  => $request->alt_phone,
                'emergency_contact_name' => $request->emergency_name,
                'emergency_contact_number' => $request->emergency_phone,
                'emergency_contact_relationship' => $request->emergency_relationship,
                'permanent_address_house'  => $request->perm_house,
                'permanent_address_city'   => $request->perm_city,
                'permanent_address_district' => $request->perm_district,
                'permanent_address_state'  => $request->perm_state,
                'permanent_address_postal_code' => $request->perm_postal,
                'current_address_house'    => $request->same_as_permanent ? $request->perm_house : $request->curr_house,
                'current_address_city'     => $request->same_as_permanent ? $request->perm_city : $request->curr_city,
                'current_address_district' => $request->same_as_permanent ? $request->perm_district : $request->curr_district,
                'current_address_state'    => $request->same_as_permanent ? $request->perm_state : $request->curr_state,
                'current_address_postal_code' => $request->same_as_permanent ? $request->perm_postal : $request->curr_postal,
                'same_as_permanent' => $request->boolean('same_as_permanent'),
                'perm_house'   => $request->perm_house,
                'perm_city'    => $request->perm_city,
                'perm_district'=> $request->perm_district,
                'perm_state'   => $request->perm_state,
                'perm_postal'  => $request->perm_postal,
                'curr_house'   => $request->same_as_permanent ? $request->perm_house : $request->curr_house,
                'curr_city'    => $request->same_as_permanent ? $request->perm_city : $request->curr_city,
                'curr_district'=> $request->same_as_permanent ? $request->perm_district : $request->curr_district,
                'curr_state'   => $request->same_as_permanent ? $request->perm_state : $request->curr_state,
                'curr_postal'  => $request->same_as_permanent ? $request->perm_postal : $request->curr_postal,
            ]);

            // 3. Identity
            $employee->identity()->create([
                'aadhaar_number'  => $request->aadhaar_number,
                'pan_number'      => $request->pan_number,
                'passport_number' => $request->passport_number,
                'passport_expiry' => $request->passport_expiry,
                'driving_license_number' => $request->driving_license,
                'voter_id'        => $request->voter_id,
                'visa_details'    => $request->visa_details,
            ]);

            // 4. Payroll Details
            $employee->payrollDetails()->create([
                'bank_name'          => $request->bank_name,
                'bank_account_number'=> $request->bank_account_number,
                'bank_ifsc_code'     => $request->ifsc_code,
                'uan_number'         => $request->uan_number,
                'esic_number'        => $request->esic_number,
                'payment_method'     => $request->payment_method ?? 'bank_transfer',
            ]);

            // 5. Education
            if ($request->has('education') && is_array($request->education)) {
                foreach ($request->education as $edu) {
                    if (!empty($edu['degree']) || !empty($edu['institution'])) {
                        $employee->education()->create([
                            'degree'          => $edu['degree'],
                            'institution'     => $edu['institution'],
                            'field_of_study'  => $edu['field_of_study'],
                            'graduation_year' => $edu['graduation_year'],
                            'grade_cgpa'      => $edu['grade'],
                        ]);
                    }
                }
            }

            // 6. Skills
            if ($request->has('skills') && is_array($request->skills)) {
                foreach ($request->skills as $skill) {
                    if (!empty($skill['skill_name'])) {
                        $employee->skills()->create([
                            'skill_name' => $skill['skill_name'],
                            'skill_type' => $skill['skill_type'],
                            'level'      => $skill['level'],
                        ]);
                    }
                }
            }

            // 7. Assets
            if ($request->has('assets') && is_array($request->assets)) {
                foreach ($request->assets as $asset) {
                    if (!empty($asset['asset_type'])) {
                        $employee->assets()->create([
                            'asset_type'    => $asset['asset_type'],
                            'asset_serial'  => $asset['asset_serial'],
                            'assigned_date' => !empty($asset['assigned_date']) ? $asset['assigned_date'] : date('Y-m-d'),
                        ]);
                    }
                }
            }

            DB::commit();

            $msg = "Employee {$empId} registered successfully with full profile setup.";
            if ($panel && $panel !== 'none') {
                $loginUrl = match($panel) {
                    'hr'        => '/hr/login',
                    'faculty', 'faculty_head' => '/faculty/login',
                    'ads'       => '/ads/login',
                    'admission' => '/admission/login',
                };
                $msg .= " Panel login credentials provisioned for {$loginUrl}.";
            }
            $this->logAudit('created_employee', "Registered new employee: {$employee->first_name} {$employee->last_name} (ID: {$employee->employee_id})");

            return redirect()->route('hr.employees.show', $employee->id)->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating employee: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $employee = Employee::with([
            'department', 'designation', 'account', 'reportingManager', 'branch',
            'personal', 'contact', 'identity', 'payrollDetails', 
            'education', 'skills', 'assets',
            'payrolls' => fn($q) => $q->latest()->limit(6)
        ])->findOrFail($id);
        return view('hr.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load([
            'personal', 'contact', 'identity', 'payrollDetails',
            'education', 'skills', 'assets'
        ]);

        $departments = Department::where('is_active', true)
            ->with(['designations' => fn ($q) => $q->where('is_active', true)->orderBy('name')])
            ->orderBy('name')
            ->get();
        $managers = Employee::where('is_active', true)->where('id', '!=', $employee->id)->orderBy('first_name')->get();
        $branches = Branch::where('is_active', true)->orderBy('name')->get();

        return view('hr.employees.edit', compact('employee', 'departments', 'managers', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:employees,email,' . $employee->id,
            'department_id' => 'required|exists:departments,id',
            'designation_id'=> 'required|exists:designations,id',
            'joining_date'  => 'required|date',
            'branch_id'     => 'required|exists:branches,id',
            'panel_access'  => 'nullable|in:none,hr,faculty,faculty_head,ads,admission',
            'panel_password' => 'nullable|string|min:6',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Handle Photo Update
            if ($request->hasFile('profile_photo')) {
                if ($employee->profile_photo) {
                    Storage::disk('public')->delete($employee->profile_photo);
                }
                $photoPath = $request->file('profile_photo')->store('employees/photos', 'public');
                $employee->profile_photo = $photoPath;
            }

            // Handle Panel Access provisioning for existing employees who don't have an account
            $panel = $request->panel_access;
            if ($panel && $panel !== 'none' && !$employee->account_type) {
                $tableName = match($panel) {
                    'hr'        => 'hr_users',
                    'faculty', 'faculty_head' => 'users',
                    'ads'       => 'ads_users',
                    'admission' => 'admission_users',
                };
                if (DB::table($tableName)->where('email', $request->email)->exists()) {
                    return back()->withInput()->with('error', "An account with this email already exists in the {$panel} panel.");
                }

                $name = trim($request->first_name . ' ' . $request->last_name);
                $password = Hash::make($request->panel_password ?? '12345678');

                if ($panel === 'hr') {
                    $user = HrUser::create(['name' => $name, 'email' => $request->email, 'password' => $password, 'role' => 'hr_executive', 'is_active' => true]);
                    $employee->account_type = HrUser::class; $employee->account_id = $user->id;
                } elseif ($panel === 'faculty') {
                    $user = User::create(['name' => $name, 'email' => $request->email, 'password' => $password, 'role' => 'faculty']);
                    $employee->account_type = User::class; $employee->account_id = $user->id;
                } elseif ($panel === 'faculty_head') {
                    $user = User::create(['name' => $name, 'email' => $request->email, 'password' => $password, 'role' => 'faculty_head']);
                    $employee->account_type = User::class; $employee->account_id = $user->id;
                } elseif ($panel === 'ads') {
                    $user = AdsUser::create(['name' => $name, 'email' => $request->email, 'password' => $password, 'role' => 'ads_manager', 'is_active' => true]);
                    $employee->account_type = AdsUser::class; $employee->account_id = $user->id;
                } elseif ($panel === 'admission') {
                    $user = AdmissionUser::create(['name' => $name, 'email' => $request->email, 'password' => $password, 'role' => 'member', 'is_active' => true]);
                    $employee->account_type = AdmissionUser::class; $employee->account_id = $user->id;
                }
            }

            // Update Employee Core Record
            $employee->update([
                'first_name'     => $request->first_name,
                'middle_name'    => $request->middle_name,
                'last_name'      => $request->last_name,
                'email'          => $request->email,
                'official_email' => $request->official_email,
                'phone'          => $request->phone,
                'department_id'  => $request->department_id,
                'designation_id' => $request->designation_id,
                'joining_date'   => $request->joining_date,
                'date_of_birth'  => $request->date_of_birth,
                'employee_type'  => $request->employee_type ?? 'full_time',
                'work_location'  => $request->work_location,
                'office_branch'  => $request->branch_id ? Branch::find($request->branch_id)?->name : null,
                'branch_id'      => $request->branch_id,
                'reporting_manager_id' => $request->reporting_manager_id,
                'probation_period' => $request->probation_period,
                'confirmation_date'=> $request->confirmation_date,
                'account_type'   => $employee->account_type,
                'account_id'     => $employee->account_id,
            ]);

            // 1. Personal
            $employee->personal()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'gender'         => $request->gender,
                    'blood_group'    => $request->blood_group,
                    'nationality'    => $request->nationality,
                    'marital_status' => $request->marital_status,
                    'spouse_name'    => $request->spouse_name,
                    'num_dependents' => $request->num_dependents ?? 0,
                ]
            );

            // 2. Contact (column names must match store() and show view)
            $employee->contact()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'alternate_phone'  => $request->alt_phone,
                    'emergency_contact_name' => $request->emergency_name,
                    'emergency_contact_number' => $request->emergency_phone,
                    'emergency_contact_relationship' => $request->emergency_relationship,
                    'permanent_address_house'  => $request->perm_house,
                    'permanent_address_city'   => $request->perm_city,
                    'permanent_address_district' => $request->perm_district,
                    'permanent_address_state'  => $request->perm_state,
                    'permanent_address_postal_code' => $request->perm_postal,
                    'current_address_house'    => $request->boolean('same_as_permanent') ? $request->perm_house : $request->curr_house,
                    'current_address_city'     => $request->boolean('same_as_permanent') ? $request->perm_city : $request->curr_city,
                    'current_address_district' => $request->boolean('same_as_permanent') ? $request->perm_district : $request->curr_district,
                    'current_address_state'    => $request->boolean('same_as_permanent') ? $request->perm_state : $request->curr_state,
                    'current_address_postal_code' => $request->boolean('same_as_permanent') ? $request->perm_postal : $request->curr_postal,
                    'same_as_permanent' => $request->boolean('same_as_permanent'),
                    // Legacy short address columns kept in sync for older records/views
                    'perm_house'   => $request->perm_house,
                    'perm_city'    => $request->perm_city,
                    'perm_district'=> $request->perm_district,
                    'perm_state'   => $request->perm_state,
                    'perm_postal'  => $request->perm_postal,
                    'curr_house'   => $request->boolean('same_as_permanent') ? $request->perm_house : $request->curr_house,
                    'curr_city'    => $request->boolean('same_as_permanent') ? $request->perm_city : $request->curr_city,
                    'curr_district'=> $request->boolean('same_as_permanent') ? $request->perm_district : $request->curr_district,
                    'curr_state'   => $request->boolean('same_as_permanent') ? $request->perm_state : $request->curr_state,
                    'curr_postal'  => $request->boolean('same_as_permanent') ? $request->perm_postal : $request->curr_postal,
                ]
            );

            // 3. Identity
            $employee->identity()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'aadhaar_number'  => $request->aadhaar_number,
                    'pan_number'      => $request->pan_number,
                    'passport_number' => $request->passport_number,
                    'passport_expiry' => $request->passport_expiry,
                    'driving_license_number' => $request->driving_license,
                    'voter_id'        => $request->voter_id,
                    'visa_details'    => $request->visa_details,
                ]
            );

            // 4. Payroll Details
            $employee->payrollDetails()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'bank_name'           => $request->bank_name,
                    'bank_account_number' => $request->bank_account_number,
                    'bank_ifsc_code'      => $request->ifsc_code,
                    'uan_number'          => $request->uan_number,
                    'esic_number'         => $request->esic_number,
                    'payment_method'      => $request->payment_method ?? 'bank_transfer',
                ]
            );

            // Dynamic rows: replace only when the section was submitted with mapped fields
            $employee->education()->delete();
            if ($request->has('education') && is_array($request->education)) {
                foreach ($request->education as $edu) {
                    if (!empty($edu['degree']) || !empty($edu['institution'])) {
                        $employee->education()->create([
                            'degree'          => $edu['degree'] ?? null,
                            'institution'     => $edu['institution'] ?? null,
                            'field_of_study'  => $edu['field_of_study'] ?? null,
                            'graduation_year' => $edu['graduation_year'] ?? null,
                            'grade_cgpa'      => $edu['grade'] ?? null,
                        ]);
                    }
                }
            }

            $employee->skills()->delete();
            if ($request->has('skills') && is_array($request->skills)) {
                foreach ($request->skills as $skill) {
                    if (!empty($skill['skill_name'])) {
                        $employee->skills()->create([
                            'skill_name' => $skill['skill_name'],
                            'skill_type' => $skill['skill_type'] ?? 'technical',
                            'level'      => $skill['level'] ?? null,
                        ]);
                    }
                }
            }

            $employee->assets()->delete();
            if ($request->has('assets') && is_array($request->assets)) {
                foreach ($request->assets as $asset) {
                    if (!empty($asset['asset_type']) || !empty($asset['asset_serial'])) {
                        $employee->assets()->create([
                            'asset_type'    => $asset['asset_type'] ?? null,
                            'asset_serial'  => $asset['asset_serial'] ?? null,
                            'assigned_date' => !empty($asset['assigned_date']) ? $asset['assigned_date'] : null,
                        ]);
                    }
                }
            }

            // Reset Password Logic
            if ($request->filled('new_panel_password') && $employee->account) {
                $employee->account->update([
                    'password' => \Illuminate\Support\Facades\Hash::make($request->new_panel_password)
                ]);
            }

            DB::commit();
            $this->logAudit('updated_employee', "Updated employee profile: {$employee->first_name} {$employee->last_name} (ID: {$employee->employee_id})");
            return redirect()->route('hr.employees.show', $employee->id)->with('success', 'Employee profile fully updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Update Failed: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->update(['is_active' => false]);
        
        // Also deactivate the panel account if it exists
        if ($employee->account && method_exists($employee->account, 'update')) {
            try {
                $employee->account->update(['is_active' => false]);
            } catch (\Exception $e) {
                // 'users' table might not have is_active, skip
            }
        }
        $this->logAudit('deactivated_employee', "Deactivated employee: {$employee->first_name} {$employee->last_name} (ID: {$employee->employee_id})");
        
        return redirect()->route('hr.employees.index')->with('success', 'Employee deactivated.');
    }

    public function toggleStatus($id)
    {
        $employee = Employee::findOrFail($id);
        $newStatus = !$employee->is_active;
        
        $employee->update(['is_active' => $newStatus]);
        
        // Sync the status with the panel account
        if ($employee->account && method_exists($employee->account, 'update')) {
            try {
                $employee->account->update(['is_active' => $newStatus]);
            } catch (\Exception $e) {
                // Some panel tables (like users) might not have 'is_active', safely ignore
            }
        }
        
        $statusText = $newStatus ? 'Activated' : 'Deactivated';
        return back()->with('success', "Employee profile and panel access {$statusText}.");
    }

    // JSON endpoint for dynamic designation load
    public function getDesignations($departmentId)
    {
        $designations = Designation::where('department_id', $departmentId)->where('is_active', true)->orderBy('name')->get();
        return response()->json($designations);
    }
}
