@extends('hr.layouts.app')
@section('title', 'Register Employee')
@section('page_title', 'Employees')

@section('content')
<div class="max-w-6xl mx-auto" x-data="employeeForm" x-init="initData()">

    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50 p-1 rounded-2xl">
        <div class="flex items-center gap-3">
            <a href="{{ route('hr.employees.index') }}" class="inline-flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors bg-white w-10 h-10 border border-slate-200 rounded-xl shadow-sm shrink-0 mr-2" title="Back to Employees">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Register New Employee</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Complete all sections to create a comprehensive employee profile.</p>
            </div>
        </div>
        <div class="flex items-center gap-3 ml-auto md:ml-0">
            <button @click="if(tab > 1) tab--" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 font-bold rounded-lg text-sm hover:bg-slate-50 transition-colors">Previous</button>
            <button @click="if(tab < 9) tab++" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 font-bold rounded-lg text-sm hover:bg-slate-50 transition-colors">Next Section</button>
            <button type="submit" form="employeeForm" class="px-6 py-2 btn-primary font-bold rounded-lg text-sm shadow-sm hover:bg-orange-600 transition-colors">Save Employee</button>
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

    <form id="employeeForm" @submit="validateForm" action="{{ route('hr.employees.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-6 items-start">
        @csrf
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
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none text-slate-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none text-slate-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Last Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none text-slate-900 font-medium">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none text-slate-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Official Email</label>
                        <input type="email" name="official_email" value="{{ old('official_email') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none text-slate-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Gender <span class="text-rose-500">*</span></label>
                        <select name="gender" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium appearance-none">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Blood Group <span class="text-rose-500">*</span></label>
                        <input type="text" name="blood_group" required value="{{ old('blood_group') }}" placeholder="e.g. O+" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nationality <span class="text-rose-500">*</span></label>
                        <input type="text" name="nationality" required value="{{ old('nationality', 'Indian') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Marital Status <span class="text-rose-500">*</span></label>
                        <select name="marital_status" x-model="maritalStatus" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium appearance-none">
                            <option value="">Select</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="divorced">Divorced</option>
                            <option value="widowed">Widowed</option>
                        </select>
                    </div>
                    <div x-show="maritalStatus === 'married'">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Spouse Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="spouse_name" :required="maritalStatus === 'married'" value="{{ old('spouse_name') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium">
                    </div>
                    <div x-show="maritalStatus !== 'single'">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Number of Dependents</label>
                        <input type="number" name="num_dependents" value="{{ old('num_dependents', 0) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none text-slate-900 font-medium">
                    </div>
                </div>
            </div>

            <!-- TAB 2: Contact -->
            <div x-show="tab === 2" x-transition.opacity style="display: none;">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-3">Contact & Address</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Primary Phone <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" required value="{{ old('phone') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alternate Phone <span class="text-rose-500">*</span></label>
                        <input type="text" name="alt_phone" required value="{{ old('alt_phone') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                </div>

                <h4 class="text-sm font-black uppercase text-slate-400 mb-4">Emergency Contact</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="emergency_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Phone Number <span class="text-rose-500">*</span></label>
                        <input type="text" name="emergency_phone" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Relationship <span class="text-rose-500">*</span></label>
                        <input type="text" name="emergency_relationship" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Permanent Address -->
                    <div>
                        <h4 class="text-sm font-black uppercase text-slate-400 mb-4">Permanent Address</h4>
                        <div class="space-y-4">
                            <input type="text" name="perm_house" required placeholder="House No / Street *" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="perm_city" required placeholder="City *" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                                <input type="text" name="perm_district" required placeholder="District *" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                                <input type="text" name="perm_state" required placeholder="State *" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                                <input type="text" name="perm_postal" required placeholder="Postal Code *" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
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
                            <input type="text" name="curr_house" :required="!sameAsPermanent" placeholder="House No / Street *" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="curr_city" :required="!sameAsPermanent" placeholder="City *" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                                <input type="text" name="curr_district" :required="!sameAsPermanent" placeholder="District *" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                                <input type="text" name="curr_state" :required="!sameAsPermanent" placeholder="State *" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
                                <input type="text" name="curr_postal" :required="!sameAsPermanent" placeholder="Postal Code *" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium">
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
                        <label class="block text-sm font-bold text-slate-700 mb-2">Aadhaar Number <span class="text-rose-500">*</span></label>
                        <input type="text" name="aadhaar_number" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">PAN Number</label>
                        <input type="text" name="pan_number" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Voter ID</label>
                        <input type="text" name="voter_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Driving License</label>
                        <input type="text" name="driving_license" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-slate-100">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Passport Number</label>
                            <input type="text" name="passport_number" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Passport Expiry</label>
                            <input type="date" name="passport_expiry" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Visa Details</label>
                            <input type="text" name="visa_details" placeholder="If applicable" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
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
                        :department-id="old('department_id')"
                        :designation-id="old('designation_id')"
                    />

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Joining Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div x-show="employeeType === 'probation'" x-transition>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Probation Period (Days)</label>
                        <input type="number" name="probation_period" placeholder="e.g. 90" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>
                    <div x-show="employeeType === 'probation'" x-transition>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Confirmation Date</label>
                        <input type="date" name="confirmation_date" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Work Location <span class="text-rose-500">*</span></label>
                        <select name="work_location" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium appearance-none">
                            <option value="">Select Location</option>
                            <option value="On-Site">On-Site</option>
                            <option value="Remote">Remote</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Office Branch <span class="text-rose-500">*</span></label>
                        <select name="branch_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium appearance-none">
                            <option value="">Select Branch</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Reporting Manager <span class="text-rose-500">*</span></label>
                        <select name="reporting_manager_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium appearance-none">
                            <option value="">Select Manager</option>
                            @foreach($managers as $m)
                                <option value="{{ $m->id }}">{{ $m->first_name }} {{ $m->last_name }} ({{ $m->employee_id }})</option>
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
                                <input type="text" x-bind:name="'education['+index+'][degree]'" placeholder="Degree (e.g. MBA)" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <input type="text" x-bind:name="'education['+index+'][institution]'" placeholder="Institution" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <input type="text" x-bind:name="'education['+index+'][field_of_study]'" placeholder="Field of Study" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <input type="number" x-bind:name="'education['+index+'][graduation_year]'" placeholder="Year" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <input type="text" x-bind:name="'education['+index+'][grade]'" placeholder="Grade / CGPA" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
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
                                <input type="text" x-bind:name="'skills['+index+'][skill_name]'" placeholder="Skill Name (e.g. PHP, English)" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <select x-bind:name="'skills['+index+'][skill_type]'" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium appearance-none">
                                    <option value="technical">Technical</option>
                                    <option value="soft">Soft Skill</option>
                                    <option value="language">Language</option>
                                    <option value="certification">Certification</option>
                                </select>
                                <select x-bind:name="'skills['+index+'][level]'" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium appearance-none">
                                    <option value="">Select Level</option>
                                    <option value="Beginner">Beginner</option>
                                    <option value="Intermediate">Intermediate</option>
                                    <option value="Expert">Expert</option>
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
                <div class="mb-6 max-w-sm">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Payment Method <span class="text-rose-500">*</span></label>
                    <select name="payment_method" x-model="paymentMethod" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium appearance-none text-slate-900">
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cheque">Cheque</option>
                        <option value="cash">Cash</option>
                    </select>
                </div>

                <div x-show="paymentMethod !== 'cash'" x-transition class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6 pb-6 border-b border-slate-100">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Bank Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="bank_name" :required="paymentMethod !== 'cash'" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium text-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">IFSC Code <span class="text-rose-500">*</span></label>
                        <input type="text" name="ifsc_code" :required="paymentMethod !== 'cash'" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium text-slate-900">
                    </div>
                    <div class="hidden lg:block"></div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Account Number <span class="text-rose-500">*</span></label>
                        <input type="password" name="bank_account_number" x-model="bankAccountNumber" :required="paymentMethod !== 'cash'" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium text-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Confirm Account Number <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="confirmBankAccountNumber" :required="paymentMethod !== 'cash'" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium text-slate-900">
                        <span x-show="bankAccountNumber && confirmBankAccountNumber && bankAccountNumber !== confirmBankAccountNumber" class="text-xs font-bold text-rose-500 mt-1 block">The account number is mismatch!</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">UAN Number</label>
                        <input type="text" name="uan_number" placeholder="PF Number" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium text-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ESIC Number</label>
                        <input type="text" name="esic_number" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 outline-none font-medium text-slate-900">
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
                                <input type="text" x-bind:name="'assets['+index+'][asset_type]'" placeholder="Asset Type (e.g. Laptop)" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <input type="text" x-bind:name="'assets['+index+'][asset_serial]'" placeholder="Serial # or Identifier" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                                <input type="date" x-bind:name="'assets['+index+'][assigned_date]'" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 font-medium">
                            </div>
                            <button type="button" @click="removeAsset(asset.id)" class="text-rose-500 p-2 hover:bg-rose-50 rounded-lg shrink-0 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z"/></svg>
                            </button>
                        </div>
                    </template>
                </div>
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
            { id: 9, name: '9. Assets' }
        ],
        // Dynamic Rows State
        educations: {!! old('education') ? json_encode(collect(old('education'))->map(function($e, $k){ $e['id'] = $e['id'] ?? time() + $k; return $e; })->values()->all()) : '[ { "id": 1 } ]' !!},
        addEducation() { this.educations.push({ id: Date.now() }); },
        removeEducation(id) { this.educations = this.educations.filter(e => e.id !== id); },

        skills: {!! old('skills') ? json_encode(collect(old('skills'))->map(function($s, $k){ $s['id'] = $s['id'] ?? time() + $k; return $s; })->values()->all()) : '[ { "id": 1 } ]' !!},
        addSkill() { this.skills.push({ id: Date.now() }); },
        removeSkill(id) { this.skills = this.skills.filter(s => s.id !== id); },

        assets: {!! old('assets') ? json_encode(collect(old('assets'))->map(function($a, $k){ $a['id'] = $a['id'] ?? time() + $k; return $a; })->values()->all()) : '[ { "id": 1 } ]' !!},
        addAsset() { this.assets.push({ id: Date.now() }); },
        removeAsset(id) { this.assets = this.assets.filter(a => a.id !== id); },

        panelAccess: '{!! old('panel_access', 'none') !!}',
        employeeType: '{!! old('employee_type', 'full_time') !!}',
        sameAsPermanent: false,

        maritalStatus: '{!! old('marital_status', '') !!}',
        paymentMethod: '{!! old('payment_method', 'bank_transfer') !!}',
        bankAccountNumber: '{!! old('bank_account_number', '') !!}',
        confirmBankAccountNumber: '{!! old('bank_account_number', '') !!}',
        
        validateForm(e) {
            if (this.educations.length === 0) {
                e.preventDefault();
                alert('Must need to add education details in atleast one row');
                this.tab = 6;
                return;
            }
            if (this.skills.length === 0) {
                e.preventDefault();
                alert('Must need to add skills details in atleast one row');
                this.tab = 7;
                return;
            }
            if (this.paymentMethod !== 'cash' && this.bankAccountNumber !== this.confirmBankAccountNumber) {
                e.preventDefault();
                alert('The account number is mismatch!');
                this.tab = 8;
                return;
            }
        },

        initData() {}
    }));
});
</script>
@endsection
