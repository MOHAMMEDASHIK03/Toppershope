@extends('hr.layouts.app')
@section('title', $employee->first_name . ' ' . $employee->last_name)
@section('page_title', 'Employee Profile')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ tab: 'overview' }">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between gap-4">
        <a href="{{ route('hr.employees.index') }}" class="inline-flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors bg-white w-10 h-10 border border-slate-200 rounded-xl shadow-sm shrink-0" title="Back to Employees">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"/></svg>
        </a>
        <div class="flex gap-3 ml-auto">
            <form action="{{ route('hr.employees.toggle-status', $employee->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit"
                        onclick="return confirm('Change status for this employee?');"
                        class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-sm shadow-sm hover:bg-slate-50 transition-colors">
                    {{ $employee->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
            <a href="{{ route('hr.employees.edit', $employee->id) }}" class="px-5 py-2.5 btn-primary font-bold rounded-xl text-sm shadow-sm hover:bg-orange-600 transition-colors">
                Edit Protocol
            </a>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-6 items-start">
        
        <!-- Sidebar Navigation -->
        <div class="w-full md:w-64 shrink-0 bg-white border border-slate-100 rounded-2xl p-2 shadow-sm sticky top-6 hidden md:block select-none mt-[100px]">
            <template x-for="t in [
                { id: 'overview', name: 'Profile Overview' },
                { id: 'personal', name: 'Personal Details' },
                { id: 'contact', name: 'Contact & Address' },
                { id: 'employment', name: 'Employment Details' },
                { id: 'education', name: 'Education' },
                { id: 'skills', name: 'Skills' },
                { id: 'payroll', name: 'Bank & Payroll' },
                { id: 'identity', name: 'Identity Docs' },
                { id: 'assets', name: 'Company Assets' }
            ]" :key="t.id">
                <button type="button" @click="tab = t.id"
                        :class="tab === t.id ? 'bg-orange-50 text-indigo-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium'"
                        class="w-full text-left px-4 py-3 rounded-xl text-sm transition-colors flex items-center justify-between mb-1">
                    <span x-text="t.name"></span>
                    <svg x-show="tab === t.id" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z"/></svg>
                </button>
            </template>
        </div>

        <div class="flex-1 w-full space-y-6">
            {{-- Persistent Profile Hero Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-50/50 to-white pointer-events-none"></div>
                <div class="relative flex flex-col md:flex-row items-center md:items-start gap-6">
                    @if($employee->profile_photo)
                        <img src="{{ Storage::url($employee->profile_photo) }}" class="w-24 h-24 rounded-2xl object-cover shadow-sm ring-4 ring-white border border-slate-100 shrink-0">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-orange-100 text-indigo-700 font-black text-3xl flex items-center justify-center shrink-0 shadow-sm ring-4 ring-white border border-slate-100">
                            {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="text-center md:text-left flex-1 min-w-0">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-black text-slate-800">{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</h2>
                                <p class="text-slate-500 font-medium mt-1">{{ $employee->designation->name ?? 'Designation Pending' }} at {{ $employee->department->name ?? 'Department Pending' }}</p>
                            </div>
                            <div class="flex items-center gap-2 justify-center md:justify-end flex-wrap">
                                <span class="font-mono text-sm font-bold bg-white border border-slate-200 text-slate-600 px-3 py-1 rounded-xl shadow-sm">{{ $employee->employee_id }}</span>
                                @if($employee->is_active)
                                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-xl shadow-sm inline-flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active</span>
                                @else
                                    <span class="text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200 px-3 py-1 rounded-xl shadow-sm inline-flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-wrap text-sm gap-x-6 gap-y-3 mt-6 border-t border-slate-100 pt-5">
                            <div class="flex items-center gap-2 text-slate-600">
                                <svg class="text-slate-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M224,48H32a8,8,0,0,0-8,8V192a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A8,8,0,0,0,224,48ZM98.71,128,40,181.81V74.19Zm11.84,10.85,12,11.05a8,8,0,0,0,10.82,0l12-11.05,58,53.15H52.57ZM157.29,128,216,74.18V181.82Z"/></svg>
                                <span>{{ $employee->email }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-600">
                                <svg class="text-slate-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M222.37,158.46l-47.11-21.11-.13-.06a16,16,0,0,0-15.17,1.4,8.12,8.12,0,0,0-.75.56L134.87,160c-15.42-7.49-31.34-23.41-38.83-38.83l20.78-24.34a8.12,8.12,0,0,0,.56-.75,16,16,0,0,0,1.4-15.17l-.06-.13L97.63,33.67a16,16,0,0,0-15.34-9.6A18.4,18.4,0,0,0,64,32L37.76,57.65c-4.48,4.35-8.22,9.65-8.62,15.74-.29,4.3,1.14,12.56,9.58,29.93C49.19,125,75.31,162,109.81,190c20.35,16.48,39,26,56.1,28.2a26.65,26.65,0,0,0,3.39.22c22.84,0,32.48-13.88,38.25-22l23.77-28A16,16,0,0,0,222.37,158.46Z"/></svg>
                                <span>{{ $employee->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-600">
                                <svg class="text-slate-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M128,16a88.1,88.1,0,0,0-88,88c0,75.3,80,132.17,83.41,134.55a8,8,0,0,0,9.18,0C136,236.17,216,179.3,216,104A88.1,88.1,0,0,0,128,16Zm0,56a32,32,0,1,1-32,32A32,32,0,0,1,128,72Z"/></svg>
                                <span>{{ $employee->work_location ?? 'Office Location Pending' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8">
                
                <!-- OVERVIEW -->
                <div x-show="tab === 'overview'" x-transition.opacity>
                    <h3 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-3">Quick Overview</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xs tracking-wider mb-3 text-slate-400 font-black uppercase">Employment Context</h4>
                            <div class="space-y-4 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Joined On</span>
                                    <span class="text-slate-800 font-bold">{{ $employee->joining_date ? $employee->joining_date->format('M d, Y') : '—' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Emp Type</span>
                                    <span class="text-slate-800 font-bold uppercase">{{ str_replace('_', ' ', $employee->employee_type ?? 'full_time') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Line Manager</span>
                                    <span class="text-orange-600 font-bold">
                                        @if($employee->reportingManager)
                                            <a href="{{ route('hr.employees.show', $employee->reportingManager->id) }}">{{ $employee->reportingManager->first_name }} {{ $employee->reportingManager->last_name }}</a>
                                        @else
                                            —
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs tracking-wider mb-3 text-slate-400 font-black uppercase">System Access</h4>
                            @if($employee->account_type)
                                @php
                                    $panelLabel = 'Panel User';
                                    if ($employee->account_type === 'App\Models\HR\HrUser') $panelLabel = 'HR Panel Admin';
                                    if ($employee->account_type === 'App\Models\User') $panelLabel = 'Faculty / Academic Panel';
                                    if ($employee->account_type === 'App\Models\Ads\AdsUser') $panelLabel = 'Ads Campaign Manager';
                                    if ($employee->account_type === 'App\Models\Admission\AdmissionUser') $panelLabel = 'Admission CRM Member';
                                @endphp
                                <div class="p-4 bg-orange-50 border border-indigo-100 rounded-xl">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-full btn-primary flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M208,40H48A16,16,0,0,0,32,56V200a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V56A16,16,0,0,0,208,40Zm0,160H48V56H208V200ZM144,120v40a8,8,0,0,1-16,0v-8H112v8a8,8,0,0,1-16,0V120a8,8,0,0,1,8-8h32A8,8,0,0,1,144,120Zm-16,0H112v16h16Z"/></svg>
                                        </div>
                                        <p class="font-bold text-indigo-900">{{ $panelLabel }}</p>
                                    </div>
                                    <p class="text-sm text-indigo-700/80 font-medium">This employee has active panel login credentials mapped to their official email.</p>
                                </div>
                            @else
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                                    <p class="font-bold text-slate-700 mb-1">No System Access</p>
                                    <p class="text-sm text-slate-500 font-medium">This employee record is for administrative tracking only.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- PERSONAL DETAILS -->
                <div x-show="tab === 'personal'" style="display: none;" x-transition.opacity>
                    <h3 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-3">Personal Details</h3>
                    @if($employee->personal)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
                            <div>
                                <span class="block text-slate-500 font-medium mb-1">Date of Birth</span>
                                <span class="font-bold text-slate-800">{{ $employee->date_of_birth ? $employee->date_of_birth->format('M d, Y') . ' (' . $employee->age . ' yrs)' : '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-medium mb-1">Gender</span>
                                <span class="font-bold text-slate-800 capitalize">{{ $employee->personal->gender ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-medium mb-1">Blood Group</span>
                                <span class="font-bold text-slate-800">{{ $employee->personal->blood_group ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-medium mb-1">Nationality</span>
                                <span class="font-bold text-slate-800">{{ $employee->personal->nationality ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-medium mb-1">Marital Status</span>
                                <span class="font-bold text-slate-800 capitalize">{{ $employee->personal->marital_status ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-medium mb-1">Dependents</span>
                                <span class="font-bold text-slate-800">{{ $employee->personal->num_dependents ?? 0 }}</span>
                            </div>
                            <div class="lg:col-span-3">
                                <span class="block text-slate-500 font-medium mb-1">Spouse Name</span>
                                <span class="font-bold text-slate-800">{{ $employee->personal->spouse_name ?? '—' }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-slate-500 italic">Personal details not provided during registration.</p>
                    @endif
                </div>

                <!-- CONTACT & ADDRESS -->
                <div x-show="tab === 'contact'" style="display: none;" x-transition.opacity>
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-3 mb-6 border-b border-slate-100 pb-3">Contact & Address</h3>
                    @if($employee->contact)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 text-sm">
                            <div>
                                <h4 class="font-bold text-slate-800 mb-4 bg-slate-50 p-2 rounded px-3 inline-block">Phone Directory</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 font-medium">Primary Phone</span>
                                        <span class="font-bold text-slate-800">{{ $employee->phone ?? '—' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 font-medium">Alternate Phone</span>
                                        <span class="font-bold text-slate-800">{{ $employee->contact->alternate_phone ?? '—' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 font-medium">Official Email</span>
                                        <span class="font-bold text-slate-800">{{ $employee->official_email ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-bold text-rose-700 bg-rose-50 p-2 rounded px-3 inline-block mb-4">Emergency Contact</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 font-medium">Name</span>
                                        <span class="font-bold text-slate-800">{{ $employee->contact->emergency_contact_name ?? '—' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 font-medium">Relationship</span>
                                        <span class="font-bold text-slate-800">{{ $employee->contact->emergency_contact_relationship ?? '—' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 font-medium">Phone Number</span>
                                        <span class="font-bold text-slate-800">{{ $employee->contact->emergency_contact_number ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm border-t border-slate-100 pt-6">
                            <div>
                                <h4 class="font-bold text-slate-800 mb-3">Permanent Address</h4>
                                <address class="not-italic text-slate-600 leading-relaxed font-medium">
                                    {{ $employee->contact->permanent_address_house }}<br>
                                    {{ $employee->contact->permanent_address_city }}<br>
                                    {{ $employee->contact->permanent_address_district }}, {{ $employee->contact->permanent_address_state }}<br>
                                    PIN: {{ $employee->contact->permanent_address_postal_code }}
                                </address>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 mb-3">Current Address</h4>
                                <address class="not-italic text-slate-600 leading-relaxed font-medium">
                                    {{ $employee->contact->current_address_house }}<br>
                                    {{ $employee->contact->current_address_city }}<br>
                                    {{ $employee->contact->current_address_district }}, {{ $employee->contact->current_address_state }}<br>
                                    PIN: {{ $employee->contact->current_address_postal_code }}
                                </address>
                            </div>
                        </div>
                    @else
                        <p class="text-slate-500 italic">Contact details not found.</p>
                    @endif
                </div>

                <!-- EMPLOYMENT -->
                <div x-show="tab === 'employment'" style="display: none;" x-transition.opacity>
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-3 mb-6 border-b border-slate-100 pb-3">Employment Details</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8 text-sm">
                        
                        <div class="bg-slate-50 rounded-xl p-4 col-span-1 sm:col-span-2 flex items-center justify-between">
                            <div>
                                <span class="block text-slate-500 font-medium mb-1">Total Tenure</span>
                                @php
                                    $diff = $employee->joining_date ? $employee->joining_date->diff(now()) : null;
                                    if ($diff) {
                                        if ($diff->y > 0) {
                                            $tenure = $diff->y . ' yr' . ($diff->y > 1 ? 's ' : ' ') . $diff->m . ' mo' . ($diff->m > 1 ? 's' : '');
                                        } elseif ($diff->m > 0) {
                                            $tenure = $diff->m . ' mo' . ($diff->m > 1 ? 's ' : ' ') . $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
                                        } else {
                                            $tenure = $diff->d === 0 ? 'Joined Today' : $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
                                        }
                                    } else {
                                        $tenure = 'Unknown';
                                    }
                                @endphp
                                <span class="font-black text-lg text-indigo-700">{{ $tenure }}</span>
                            </div>
                            <div class="text-right">
                                <span class="block text-slate-500 font-medium mb-1">Confirmation Status</span>
                                @if($employee->employee_type !== 'probation')
                                    <span class="font-bold text-emerald-600">Confirmed</span>
                                @elseif($employee->confirmation_date && $employee->confirmation_date <= now())
                                    <span class="font-bold text-emerald-600">Confirmed ({{ $employee->confirmation_date->format('M d, Y') }})</span>
                                @else
                                    @php
                                        $probDays = $employee->probation_period ?? ($employee->confirmation_date && $employee->joining_date ? $employee->joining_date->diffInDays($employee->confirmation_date) : null);
                                    @endphp
                                    <span class="font-bold text-amber-600">On Probation ({{ $probDays ?? '—' }} Days)</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <span class="block text-slate-500 font-medium mb-1">Employee Type</span>
                            <span class="font-bold text-slate-800 uppercase">{{ str_replace('_', ' ', $employee->employee_type ?? '—') }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-500 font-medium mb-1">Office Branch</span>
                            <span class="font-bold text-slate-800">{{ $employee->branch->name ?? $employee->office_branch ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-500 font-medium mb-1">Job Role / Title</span>
                            <span class="font-bold text-slate-800">{{ $employee->designation->name ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-500 font-medium mb-1">Department</span>
                            <span class="font-bold text-slate-800">{{ $employee->department->name ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                <!-- EDUCATION -->
                <div x-show="tab === 'education'" style="display: none;" x-transition.opacity>
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-3 mb-6 border-b border-slate-100 pb-3">Education History</h3>
                    @if($employee->education->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($employee->education as $edu)
                            <div class="bg-slate-50/50 border border-slate-100 p-5 rounded-xl">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-bold text-slate-800 text-base">{{ $edu->degree }} - {{ $edu->field_of_study }}</h4>
                                        <p class="text-slate-600 text-sm mt-1">{{ $edu->institution }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block bg-slate-100 text-slate-700 font-bold px-3 py-1 text-xs rounded-full">{{ $edu->graduation_year }}</span>
                                        <p class="text-xs font-bold text-slate-500 mt-2">Grade: {{ $edu->grade }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-slate-500 italic">No education records found.</p>
                    @endif
                </div>

                <!-- SKILLS -->
                <div x-show="tab === 'skills'" style="display: none;" x-transition.opacity>
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-3 mb-6 border-b border-slate-100 pb-3">Skills & Certifications</h3>
                    @if($employee->skills->isNotEmpty())
                        <div class="flex flex-wrap gap-3">
                            @foreach($employee->skills as $skill)
                            @php
                                $color = match($skill->skill_type) {
                                    'technical' => 'bg-orange-50 text-blue-700 border-blue-200',
                                    'soft' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'language' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'certification' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default => 'bg-slate-50 text-slate-700 border-slate-200'
                                };
                            @endphp
                            <div class="border px-4 py-2.5 rounded-xl flex items-center gap-3 {{ $color }}">
                                <span class="font-bold">{{ $skill->skill_name }}</span>
                                <span class="w-1 h-4 bg-current opacity-30"></span>
                                <span class="text-xs uppercase font-black uppercase tracking-wider opacity-70">{{ $skill->proficiency_level }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-slate-500 italic">No skills recorded.</p>
                    @endif
                </div>

                <!-- PAYROLL -->
                <div x-show="tab === 'payroll'" style="display: none;" x-transition.opacity>
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-3 mb-6 border-b border-slate-100 pb-3">Bank & Payroll Configuration</h3>
                    @if($employee->payrollDetails)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-sm mb-10">
                            <div>
                                <span class="block text-slate-500 font-medium mb-1">Bank Name</span>
                                <span class="font-bold text-slate-800">{{ $employee->payrollDetails->bank_name ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-medium mb-1">Account Number</span>
                                <span class="font-bold text-slate-800 font-mono">{{ $employee->payrollDetails->bank_account_number ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-medium mb-1">IFSC Code</span>
                                <span class="font-bold text-slate-800 uppercase">{{ $employee->payrollDetails->bank_ifsc_code ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-medium mb-1">UAN / PF Number</span>
                                <span class="font-bold text-slate-800">{{ $employee->payrollDetails->uan_number ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-medium mb-1">ESIC Number</span>
                                <span class="font-bold text-slate-800">{{ $employee->payrollDetails->esic_number ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-medium mb-1">Preferred Payout</span>
                                <span class="font-bold text-slate-800 capitalize">{{ str_replace('_', ' ', $employee->payrollDetails->payment_method ?? '—') }}</span>
                            </div>
                        </div>

                        {{-- Recent Payslips View --}}
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <div class="px-5 py-4 bg-slate-50/80 border-b border-slate-200 flex justify-between items-center">
                                <h4 class="font-bold text-slate-800">Recent Payslips</h4>
                            </div>
                            <div class="divide-y divide-slate-100">
                                @forelse($employee->payrolls as $pr)
                                <div class="px-5 py-4 flex items-center justify-between hover:bg-slate-50">
                                    <div class="text-sm">
                                        <p class="font-bold text-slate-800">{{ $pr->month_year }}</p>
                                        <p class="text-xs font-medium text-slate-500">Ref: #{{ $pr->id }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="font-black text-slate-700 shrink-0">₹ {{ number_format($pr->net_payable, 2) }}</span>
                                        @if($pr->status === 'paid')
                                            <span class="px-2 py-1 bg-emerald-50 text-emerald-700 font-bold text-[10px] uppercase rounded">Paid</span>
                                        @else
                                            <span class="px-2 py-1 bg-amber-50 text-amber-700 font-bold text-[10px] uppercase rounded">Pending</span>
                                        @endif
                                        <a href="{{ route('hr.payroll.show', $pr->id) }}" class="p-2 text-orange-600 hover:bg-orange-50 rounded" title="View Payslip">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M224,152a8,8,0,0,1-8,8H136v64a8,8,0,0,1-16,0V160H40a8,8,0,0,1,0-16h80V80a8,8,0,0,1,16,0v64h80A8,8,0,0,1,224,152Z"/></svg>
                                        </a>
                                    </div>
                                </div>
                                @empty
                                <div class="px-5 py-6 text-center text-sm text-slate-500">
                                    No payroll records generated yet.
                                </div>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <p class="text-slate-500 italic">Payroll configurations not found.</p>
                    @endif
                </div>

                <!-- IDENTITY -->
                <div x-show="tab === 'identity'" style="display: none;" x-transition.opacity>
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-3 mb-6 border-b border-slate-100 pb-3">Identity Documents</h3>
                    @if($employee->identity)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
                            <div class="bg-slate-50 p-4 border border-slate-100 rounded-xl">
                                <span class="block text-xs uppercase tracking-widest font-black text-slate-400 mb-1">Aadhaar Card</span>
                                <span class="font-bold text-slate-800 text-lg tracking-wide">{{ $employee->identity->aadhaar_number ?? '—' }}</span>
                            </div>
                            <div class="bg-slate-50 p-4 border border-slate-100 rounded-xl">
                                <span class="block text-xs uppercase tracking-widest font-black text-slate-400 mb-1">PAN Card</span>
                                <span class="font-bold text-slate-800 text-lg tracking-wide">{{ $employee->identity->pan_number ?? '—' }}</span>
                            </div>
                            <div class="bg-slate-50 p-4 border border-slate-100 rounded-xl">
                                <span class="block text-xs uppercase tracking-widest font-black text-slate-400 mb-1">Driving License</span>
                                <span class="font-bold text-slate-800 text-lg tracking-wide">{{ $employee->identity->driving_license_number ?? '—' }}</span>
                            </div>
                            <div class="bg-slate-50 p-4 border border-slate-100 rounded-xl">
                                <span class="block text-xs uppercase tracking-widest font-black text-slate-400 mb-1">Voter ID</span>
                                <span class="font-bold text-slate-800 text-lg tracking-wide">{{ $employee->identity->voter_id ?? '—' }}</span>
                            </div>
                            <div class="bg-slate-50 p-4 border border-slate-100 rounded-xl sm:col-span-2 lg:col-span-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="block text-xs uppercase tracking-widest font-black text-slate-400 mb-1">Passport Details</span>
                                        <span class="font-bold text-slate-800 text-lg tracking-wide">{{ $employee->identity->passport_number ?? '—' }}</span>
                                    </div>
                                    @if($employee->identity->passport_expiry)
                                        <div class="text-right">
                                            <span class="block text-xs uppercase tracking-widest font-black text-slate-400 mb-1">Expires On</span>
                                            <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($employee->identity->passport_expiry)->format('d M, Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-slate-500 italic text-sm font-medium">No identity documents updated in the system.</p>
                    @endif
                </div>

                <!-- ASSETS -->
                <div x-show="tab === 'assets'" style="display: none;" x-transition.opacity>
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-3 mb-6 border-b border-slate-100 pb-3">Company Assets Log</h3>
                    @if($employee->assets->isNotEmpty())
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-slate-500 font-semibold text-xs uppercase">
                                    <tr>
                                        <th class="px-5 py-3">Asset Item</th>
                                        <th class="px-5 py-3">Identifier / Serial #</th>
                                        <th class="px-5 py-3">Assigned Date</th>
                                        <th class="px-5 py-3 text-right">Return Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($employee->assets as $asset)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-4 font-bold text-slate-800">{{ $asset->asset_type }}</td>
                                        <td class="px-5 py-4 font-mono text-slate-600 font-medium">{{ $asset->asset_serial }}</td>
                                        <td class="px-5 py-4 text-slate-600">{{ $asset->assigned_date ? $asset->assigned_date->format('M d, Y') : '—' }}</td>
                                        <td class="px-5 py-4 text-right">
                                            @if($asset->returned_date)
                                                <span class="px-2 py-1 bg-slate-100 text-slate-600 font-bold text-xs rounded">Returned on {{ $asset->returned_date->format('M d') }}</span>
                                            @else
                                                <span class="px-2 py-1 bg-orange-50 text-indigo-700 font-bold text-xs rounded">With Employee</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-slate-500 italic text-sm font-medium">No company assets are currently tracked against this employee.</p>
                    @endif
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
