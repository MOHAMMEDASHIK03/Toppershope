@extends('hr.layouts.app')
@section('title', 'Register Employee')
@section('page_title', 'Employees')

@section('content')
@php
    $contact = $employee->contact;
    $identity = $employee->identity;
    $payroll = $employee->payrollDetails;
    $permHouse = old('perm_house', $contact?->permanent_address_house ?? $contact?->perm_house ?? '');
    $permCity = old('perm_city', $contact?->permanent_address_city ?? $contact?->perm_city ?? '');
    $permDistrict = old('perm_district', $contact?->permanent_address_district ?? $contact?->perm_district ?? '');
    $permState = old('perm_state', $contact?->permanent_address_state ?? $contact?->perm_state ?? '');
    $permPostal = old('perm_postal', $contact?->permanent_address_postal_code ?? $contact?->perm_postal ?? '');
    $currHouse = old('curr_house', $contact?->current_address_house ?? $contact?->curr_house ?? '');
    $currCity = old('curr_city', $contact?->current_address_city ?? $contact?->curr_city ?? '');
    $currDistrict = old('curr_district', $contact?->current_address_district ?? $contact?->curr_district ?? '');
    $currState = old('curr_state', $contact?->current_address_state ?? $contact?->curr_state ?? '');
    $currPostal = old('curr_postal', $contact?->current_address_postal_code ?? $contact?->curr_postal ?? '');
@endphp
<div class="max-w-6xl mx-auto" x-data="employeeForm" x-init="initData()">

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('hr.employees.index') }}" class="inline-flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors bg-white w-10 h-10 border border-slate-200 rounded-xl shadow-sm shrink-0 mr-2" title="Back to Employees">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Edit Employee Profile</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Update employee details across all sections.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button @click="if(tab > 1) tab--" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 font-bold rounded-lg text-sm hover:bg-slate-50 transition-colors">Previous</button>
            <button @click="if(tab < 10) tab++" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 font-bold rounded-lg text-sm hover:bg-slate-50 transition-colors">Next Section</button>
            <button type="submit" form="employeeForm" class="px-6 py-2 btn-primary font-bold rounded-lg text-sm shadow-sm hover:bg-orange-600 transition-colors">Update Employee</button>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-sm">
            <p class="font-bold mb-2">Please correct the following errors:</p>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="employeeForm" action="{{ route('hr.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-6 items-start" novalidate>
        @csrf
        @method('PUT')
        <input type="hidden" name="tab" x-model="tab">

        <!-- Sidebar Tabs -->
        <div class="w-full md:w-64 shrink-0 bg-white border border-slate-100 rounded-2xl p-2 shadow-sm sticky top-6">
            <template x-for="t in tabs" :key="t.id">
                <button type="button" @click="tab = t.id"
                        :class="tab === t.id ? 'bg-orange-50 text-indigo-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                        class="w-full text-left px-4 py-3 rounded-xl text-sm transition-colors flex items-center justify-between mb-1">
                    <span x-text="t.name"></span>
                    <svg x-show="tab === t.id" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z"/></svg>
                </button>
            </template>
        </div>

        <!-- Tab Content Area -->
        <div class="flex-1 bg-white border border-slate-100 rounded-2xl shadow-sm p-6 sm:p-8 w-full">
            
            <!-- TAB 1: Personal -->
            <div x-show="tab === 1" x-transition.opacity>
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-3">Personal Information</h3>
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Profile Photo</label>
                    <input type="file" name="profile_photo" accept="image/*" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">First Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none text-slate-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $employee->middle_name) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none text-slate-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Last Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none text-slate-900 font-medium">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $employee->email) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none text-slate-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Official Email</label>
                        <input type="email" name="official_email" value="{{ old('official_email', $employee->official_email) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none text-slate-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Gender</label>
                        <select name="gender" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium appearance-none">
                            <option value="">Select Gender</option>
                            <option value="male" @selected(old('gender', $employee->personal?->gender) === 'male')>Male</option>
                            <option value="female" @selected(old('gender', $employee->personal?->gender) === 'female')>Female</option>
                            <option value="other" @selected(old('gender', $employee->personal?->gender) === 'other')>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Blood Group</label>
                        <input type="text" name="blood_group" value="{{ old('blood_group', $employee->personal?->blood_group) }}" placeholder="e.g. O+" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nationality</label>
                        <input type="text" name="nationality" value="{{ old('nationality', $employee->personal?->nationality ?? 'Indian') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Marital Status</label>
                        <select name="marital_status" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium appearance-none">
                            <option value="">Select</option>
                            <option value="single" @selected(old('marital_status', $employee->personal?->marital_status) === 'single')>Single</option>
                            <option value="married" @selected(old('marital_status', $employee->personal?->marital_status) === 'married')>Married</option>
                            <option value="divorced" @selected(old('marital_status', $employee->personal?->marital_status) === 'divorced')>Divorced</option>
                            <option value="widowed" @selected(old('marital_status', $employee->personal?->marital_status) === 'widowed')>Widowed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Spouse Name</label>
                        <input type="text" name="spouse_name" value="{{ old('spouse_name', $employee->personal?->spouse_name) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Number of Dependents</label>
                        <input type="number" name="num_dependents" value="{{ old('num_dependents', $employee->personal?->num_dependents ?? 0) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium">
                    </div>
                </div>
            </div>

            <!-- TAB 2: Contact -->
            <div x-show="tab === 2" x-transition.opacity style="display: none;">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-3">Contact & Address</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Primary Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alternate Phone</label>
                        <input type="text" name="alt_phone" value="{{ old('alt_phone', $contact?->alternate_phone ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                </div>

                <h4 class="text-sm font-black uppercase text-slate-400 mb-4">Emergency Contact</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Name</label>
                        <input type="text" name="emergency_name" value="{{ old('emergency_name', $contact?->emergency_contact_name ?? $contact?->emergency_name ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Phone Number</label>
                        <input type="text" name="emergency_phone" value="{{ old('emergency_phone', $contact?->emergency_contact_number ?? $contact?->emergency_phone ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Relationship</label>
                        <input type="text" name="emergency_relationship" value="{{ old('emergency_relationship', $contact?->emergency_contact_relationship ?? $contact?->emergency_relationship ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Permanent Address -->
                    <div>
                        <h4 class="text-sm font-black uppercase text-slate-400 mb-4">Permanent Address</h4>
                        <div class="space-y-4">
                            <input type="text" name="perm_house" value="{{ $permHouse }}" placeholder="House No / Street" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="perm_city" value="{{ $permCity }}" placeholder="City" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                                <input type="text" name="perm_district" value="{{ $permDistrict }}" placeholder="District" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                                <input type="text" name="perm_state" value="{{ $permState }}" placeholder="State" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                                <input type="text" name="perm_postal" value="{{ $permPostal }}" placeholder="Postal Code" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                            </div>
                        </div>
                    </div>
                    <!-- Current Address -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-black uppercase text-slate-400">Current Address</h4>
                            <label class="flex items-center gap-2 text-sm font-medium text-slate-600 cursor-pointer">
                                <input type="checkbox" name="same_as_permanent" x-model="sameAsPermanent" value="1" class="rounded text-orange-600 focus:ring-orange-500">
                                Same as permanent
                            </label>
                        </div>
                        <div class="space-y-4" x-show="!sameAsPermanent">
                            <input type="text" name="curr_house" value="{{ $currHouse }}" placeholder="House No / Street" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="curr_city" value="{{ $currCity }}" placeholder="City" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                                <input type="text" name="curr_district" value="{{ $currDistrict }}" placeholder="District" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                                <input type="text" name="curr_state" value="{{ $currState }}" placeholder="State" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                                <input type="text" name="curr_postal" value="{{ $currPostal }}" placeholder="Postal Code" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: Identity -->
            <div x-show="tab === 3" x-transition.opacity style="display: none;">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-3">Identity Documents</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Aadhaar Number</label>
                        <input type="text" name="aadhaar_number" value="{{ old('aadhaar_number', $identity?->aadhaar_number ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">PAN Number</label>
                        <input type="text" name="pan_number" value="{{ old('pan_number', $identity?->pan_number ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Voter ID</label>
                        <input type="text" name="voter_id" value="{{ old('voter_id', $identity?->voter_id ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Driving License</label>
                        <input type="text" name="driving_license" value="{{ old('driving_license', $identity?->driving_license_number ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-slate-100">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Passport Number</label>
                            <input type="text" name="passport_number" value="{{ old('passport_number', $identity?->passport_number ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Passport Expiry</label>
                            <input type="date" name="passport_expiry" value="{{ old('passport_expiry', $identity?->passport_expiry?->format('Y-m-d')) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Visa Details</label>
                            <input type="text" name="visa_details" value="{{ old('visa_details', $identity?->visa_details ?? '') }}" placeholder="If applicable" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: Employment -->
            <div x-show="tab === 4" x-transition.opacity style="display: none;" x-effect="if (tab === 4) { $nextTick(() => window.refreshDeptDesignationThSelect?.()); }">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-3">Employment Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Employee Type</label>
                        <select name="employee_type" x-model="employeeType" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium appearance-none">
                            <option value="full_time">Full Time</option>
                            <option value="part_time">Part Time</option>
                            <option value="contract">Contract</option>
                            <option value="intern">Intern</option>
                            <option value="probation">Probation</option>
                        </select>
                    </div>
                    <x-hr.department-designation-fields
                        :departments="$departments"
                        :department-id="old('department_id', $employee->department_id)"
                        :designation-id="old('designation_id', $employee->designation_id)"
                    />

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Joining Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="joining_date" value="{{ old('joining_date', $employee->joining_date?->format('Y-m-d')) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div x-show="employeeType === 'probation'" x-transition>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Probation Period (Days)</label>
                        <input type="number" name="probation_period" value="{{ old('probation_period', $employee->probation_period) }}" placeholder="e.g. 90" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div x-show="employeeType === 'probation'" x-transition>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Confirmation Date</label>
                        <input type="date" name="confirmation_date" value="{{ old('confirmation_date', $employee->confirmation_date?->format('Y-m-d')) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Work Location</label>
                        <input type="text" name="work_location" value="{{ old('work_location', $employee->work_location) }}" placeholder="e.g. On-site, Remote" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Office Branch <span class="text-rose-500">*</span></label>
                        <select name="branch_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium appearance-none">
                            <option value="">Select Branch</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ $employee->branch_id == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Reporting Manager</label>
                        <select name="reporting_manager_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium appearance-none">
                            <option value="">Select Manager</option>
                            @foreach($managers as $m)
                                <option value="{{ $m->id }}" @selected(old('reporting_manager_id', $employee->reporting_manager_id) == $m->id)>{{ $m->first_name }} {{ $m->last_name }} ({{ $m->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- TAB 5: Panel Access -->
            <div x-show="tab === 5" x-transition.opacity style="display: none;">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-3">Panel Access Provisioning</h3>
                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700 mb-3">Does this employee require system dashboard access?</label>
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" x-model="panelAccess" name="panel_access" value="none" class="peer sr-only">
                            <div class="px-3 py-3 rounded-xl border-2 peer-checked:border-slate-600 peer-checked:bg-slate-50 border-slate-200 bg-white text-center transition-all">
                                <span class="block text-sm font-bold peer-checked:text-slate-700 text-slate-500">None</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="panelAccess" name="panel_access" value="hr" class="peer sr-only">
                            <div class="px-3 py-3 rounded-xl border-2 peer-checked:border-rose-500 peer-checked:bg-rose-50 border-slate-200 bg-white text-center transition-all">
                                <span class="block text-sm font-bold peer-checked:text-rose-700 text-slate-600">HR Panel</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="panelAccess" name="panel_access" value="faculty" class="peer sr-only">
                            <div class="px-3 py-3 rounded-xl border-2 peer-checked:border-indigo-500 peer-checked:bg-orange-50 border-slate-200 bg-white text-center transition-all">
                                <span class="block text-sm font-bold peer-checked:text-indigo-700 text-slate-600">Faculty</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="panelAccess" name="panel_access" value="faculty_head" class="peer sr-only">
                            <div class="px-3 py-3 rounded-xl border-2 peer-checked:border-orange-500 peer-checked:bg-orange-50 border-slate-200 bg-white text-center transition-all">
                                <span class="block text-sm font-bold peer-checked:text-orange-700 text-slate-600">Faculty Head</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="panelAccess" name="panel_access" value="ads" class="peer sr-only">
                            <div class="px-3 py-3 rounded-xl border-2 peer-checked:border-blue-500 peer-checked:bg-orange-50 border-slate-200 bg-white text-center transition-all">
                                <span class="block text-sm font-bold peer-checked:text-blue-700 text-slate-600">Ads Panel</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="panelAccess" name="panel_access" value="admission" class="peer sr-only">
                            <div class="px-3 py-3 rounded-xl border-2 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 border-slate-200 bg-white text-center transition-all">
                                <span class="block text-sm font-bold peer-checked:text-emerald-700 text-slate-600">Admission</span>
                            </div>
                        </label>
                    </div>
                </div>

                @if(!$employee->account_type)
                <div x-show="panelAccess !== 'none'" x-transition class="bg-orange-50/50 rounded-xl p-5 border border-indigo-100">
                    <p class="text-sm text-indigo-800 font-medium mb-4">
                        A user account will be created automatically in the selected module using the employee's email address.
                    </p>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Initial Account Password <span class="text-rose-500" x-show="panelAccess !== 'none'">*</span></label>
                        <input type="text" name="panel_password" x-bind:required="panelAccess !== 'none'"
                               class="w-full md:w-1/2 px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:border-indigo-600 outline-none text-slate-900 font-medium"
                               placeholder="Set initial password">
                    </div>
                </div>
                @else
                <div class="bg-indigo-50/50 rounded-xl p-5 border border-indigo-100 mt-4">
                    <p class="text-sm text-indigo-800 font-medium">
                        This employee already has system access. To change their password, go to the "Reset Password" tab.
                    </p>
                </div>
                @endif
            </div>

            <!-- TAB 6: Education -->
            <div x-show="tab === 6" x-transition.opacity style="display: none;">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Education Details</h3>
                    <button type="button" @click="addEducation()" class="text-sm font-bold text-orange-600 hover:text-indigo-700">+ Add Row</button>
                </div>
                
                <div class="space-y-4">
                    <template x-for="(edu, index) in educations" :key="edu.id">
                        <div class="flex items-start gap-3">
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 flex-1">
                                <input type="text" x-bind:name="'education['+index+'][degree]'" :value="edu.degree ?? ''" placeholder="Degree (e.g. MBA)" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <input type="text" x-bind:name="'education['+index+'][institution]'" :value="edu.institution ?? ''" placeholder="Institution" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <input type="text" x-bind:name="'education['+index+'][field_of_study]'" :value="edu.field_of_study ?? ''" placeholder="Field of Study" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <input type="number" x-bind:name="'education['+index+'][graduation_year]'" :value="edu.graduation_year ?? ''" placeholder="Year" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <input type="text" x-bind:name="'education['+index+'][grade]'" :value="edu.grade ?? edu.grade_cgpa ?? ''" placeholder="Grade / CGPA" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                            </div>
                            <button type="button" @click="removeEducation(edu.id)" class="text-rose-500 p-2 hover:bg-rose-50 rounded-lg shrink-0 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z"/></svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- TAB 7: Skills -->
            <div x-show="tab === 7" x-transition.opacity style="display: none;">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Skills & Certifications</h3>
                    <button type="button" @click="addSkill()" class="text-sm font-bold text-orange-600 hover:text-indigo-700">+ Add Row</button>
                </div>

                <div class="space-y-4">
                    <template x-for="(skill, index) in skills" :key="skill.id">
                        <div class="flex items-start gap-3">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 flex-1">
                                <input type="text" x-bind:name="'skills['+index+'][skill_name]'" :value="skill.skill_name ?? ''" placeholder="Skill Name (e.g. PHP, English)" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <select x-bind:name="'skills['+index+'][skill_type]'" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium appearance-none">
                                    <option value="technical" :selected="(skill.skill_type ?? 'technical') === 'technical'">Technical</option>
                                    <option value="soft" :selected="skill.skill_type === 'soft'">Soft Skill</option>
                                    <option value="language" :selected="skill.skill_type === 'language'">Language</option>
                                    <option value="certification" :selected="skill.skill_type === 'certification'">Certification</option>
                                </select>
                                <select x-bind:name="'skills['+index+'][level]'" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium appearance-none">
                                    <option value="">Select Level</option>
                                    <option value="Beginner" :selected="(skill.level ?? skill.proficiency_level) === 'Beginner'">Beginner</option>
                                    <option value="Intermediate" :selected="(skill.level ?? skill.proficiency_level) === 'Intermediate'">Intermediate</option>
                                    <option value="Expert" :selected="(skill.level ?? skill.proficiency_level) === 'Expert'">Expert</option>
                                </select>
                            </div>
                            <button type="button" @click="removeSkill(skill.id)" class="text-rose-500 p-2 hover:bg-rose-50 rounded-lg shrink-0 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z"/></svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- TAB 8: Bank / Payroll -->
            <div x-show="tab === 8" x-transition.opacity style="display: none;">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-3">Bank & Payroll Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $payroll?->bank_name ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium text-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Account Number</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $payroll?->bank_account_number ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium text-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">IFSC Code</label>
                        <input type="text" name="ifsc_code" value="{{ old('ifsc_code', $payroll?->bank_ifsc_code ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium text-slate-900">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">UAN Number</label>
                        <input type="text" name="uan_number" value="{{ old('uan_number', $payroll?->uan_number ?? '') }}" placeholder="PF Number" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium text-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ESIC Number</label>
                        <input type="text" name="esic_number" value="{{ old('esic_number', $payroll?->esic_number ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium text-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Payment Method</label>
                        <select name="payment_method" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium appearance-none text-slate-900">
                            <option value="bank_transfer" @selected(old('payment_method', $payroll?->payment_method ?? 'bank_transfer') === 'bank_transfer')>Bank Transfer</option>
                            <option value="cheque" @selected(old('payment_method', $payroll?->payment_method ?? '') === 'cheque')>Cheque</option>
                            <option value="cash" @selected(old('payment_method', $payroll?->payment_method ?? '') === 'cash')>Cash</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- TAB 9: Assets -->
            <div x-show="tab === 9" x-transition.opacity style="display: none;">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Company Assets (Initial Assignment)</h3>
                    <button type="button" @click="addAsset()" class="text-sm font-bold text-orange-600 hover:text-indigo-700">+ Add Row</button>
                </div>
                
                <div class="space-y-4 mb-8">
                    <template x-for="(asset, index) in assets" :key="asset.id">
                        <div class="flex items-start gap-3">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 flex-1">
                                <input type="text" x-bind:name="'assets['+index+'][asset_type]'" :value="asset.asset_type ?? ''" placeholder="Asset Type (e.g. Laptop)" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <input type="text" x-bind:name="'assets['+index+'][asset_serial]'" :value="asset.asset_serial ?? ''" placeholder="Serial # or Identifier" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <input type="date" x-bind:name="'assets['+index+'][assigned_date]'" :value="asset.assigned_date ? String(asset.assigned_date).substring(0, 10) : ''" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                            </div>
                            <button type="button" @click="removeAsset(asset.id)" class="text-rose-500 p-2 hover:bg-rose-50 rounded-lg shrink-0 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z"/></svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- TAB 10: Reset Password -->
            <div x-show="tab === 10" x-transition.opacity style="display: none;">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-3">Reset Employee Panel Password</h3>
                
                @if($employee->account_type)
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <div class="text-amber-600 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M224,48V96a8,8,0,0,1-8,8H168a8,8,0,0,1,0-16h28.69L182.06,73.37A80,80,0,1,0,184.8,184.73a8,8,0,0,1,10.93,11.67A96,96,0,1,1,208,80.35V48a8,8,0,0,1,16,0Z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-amber-800 text-sm">Warning: Immediate Effect</h4>
                                <p class="text-xs text-amber-700 font-medium mt-1">Resetting the password will immediately affect the employee, requiring them to use the new password to sign in.</p>
                            </div>
                        </div>
                    </div>

                    <div class="max-w-md">
                        <label class="block text-sm font-bold text-slate-700 mb-2">New Password <span class="text-rose-500">*</span></label>
                        <input type="text" name="new_panel_password" placeholder="Enter new password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none font-medium text-slate-900 mb-2">
                        <p class="text-xs font-medium text-slate-500">Leaving this field empty will keep the current password unchanged.</p>
                    </div>
                @else
                    <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="w-16 h-16 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M176,16H80A24,24,0,0,0,56,40V216a24,24,0,0,0,24,24h96a24,24,0,0,0,24-24V40A24,24,0,0,0,176,16Zm8,200a8,8,0,0,1-8,8H80a8,8,0,0,1-8-8V40a8,8,0,0,1,8-8h96a8,8,0,0,1,8,8Z"/></svg>
                        </div>
                        <h4 class="font-bold text-slate-700 text-base mb-1">No Panel Access</h4>
                        <p class="text-sm font-medium text-slate-500">This employee does not have an active panel account. You can assign them one in the "Panel Access" tab.</p>
                    </div>
                @endif
            </div>

        </div>
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('employeeForm', () => ({
        tab: {{ old('tab', 1) }},
        tabs: [
            { id: 1, name: '1. Personal' },
            { id: 2, name: '2. Contact & Address' },
            { id: 3, name: '3. Identity Docs' },
            { id: 4, name: '4. Employment' },
            { id: 5, name: '5. Panel Access' },
            { id: 6, name: '6. Education' },
            { id: 7, name: '7. Skills' },
            { id: 8, name: '8. Bank & Payroll' },
            { id: 9, name: '9. Assets' },
            { id: 10, name: '10. Reset Password' }
        ],
        // Dynamic Rows State
        educations: {!! old('education') ? json_encode(collect(old('education'))->map(function($e, $k){ $e['id'] = $e['id'] ?? time() + $k; return $e; })->values()->all()) : ($employee->education->count() ? collect($employee->education)->map(function($e){ $r = $e->toArray(); $r['id'] = $e->id ?? time(); $r['grade'] = $e->grade ?? $e->grade_cgpa ?? null; return $r; })->toJson() : '[ { "id": 1 } ]') !!},
        addEducation() { this.educations.push({ id: Date.now() }); },
        removeEducation(id) { this.educations = this.educations.filter(e => e.id !== id); },

        skills: {!! old('skills') ? json_encode(collect(old('skills'))->map(function($s, $k){ $s['id'] = $s['id'] ?? time() + $k; return $s; })->values()->all()) : ($employee->skills->count() ? collect($employee->skills)->map(function($s){ $r = $s->toArray(); $r['id'] = $s->id ?? time(); return $r; })->toJson() : '[ { "id": 1 } ]') !!},
        addSkill() { this.skills.push({ id: Date.now() }); },
        removeSkill(id) { this.skills = this.skills.filter(s => s.id !== id); },

        assets: {!! old('assets') ? json_encode(collect(old('assets'))->map(function($a, $k){ $a['id'] = $a['id'] ?? time() + $k; return $a; })->values()->all()) : ($employee->assets->count() ? collect($employee->assets)->map(function($a){ $r = $a->toArray(); $r['id'] = $a->id ?? time(); return $r; })->toJson() : '[ { "id": 1 } ]') !!},
        addAsset() { this.assets.push({ id: Date.now() }); },
        removeAsset(id) { this.assets = this.assets.filter(a => a.id !== id); },

        panelAccess: '{!! old('panel_access', $employee->account_type ? (str_contains($employee->account_type, "HrUser") ? "hr" : (str_contains($employee->account_type, "AdsUser") ? "ads" : (str_contains($employee->account_type, "AdmissionUser") ? "admission" : ($employee->account?->role === 'faculty_head' ? 'faculty_head' : 'faculty')))) : "none") !!}',
        employeeType: '{!! old('employee_type', $employee->employee_type ?? 'full_time') !!}',
        sameAsPermanent: false,

        initData() {}
    }));
});
</script>
@endsection
