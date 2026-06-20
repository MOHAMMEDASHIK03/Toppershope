@extends('layouts.public')

@section('content')
<div class="min-h-[86vh] relative overflow-hidden py-10 sm:py-14">
    <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-primary-100/60 blur-3xl rounded-full -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[26rem] h-[26rem] bg-primary-100/60 blur-3xl rounded-full translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid lg:grid-cols-5 gap-8 items-stretch">
            <div class="hidden lg:flex lg:col-span-2 rounded-3xl bg-gradient-to-br from-[#111827] via-[#1f2937] to-[#312e81] text-white p-10 shadow-[0_20px_55px_rgba(17,24,39,0.35)]">
                <div class="my-auto">
                    <span class="inline-flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-[0.14em] bg-white/10 border border-white/20 rounded-full px-3 py-1 mb-6">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-200"></span>
                        New Student Onboarding
                    </span>
                    <h2 class="text-4xl font-black leading-tight mb-4">Build Your Success Plan From Day One</h2>
                    <p class="text-white/85 leading-relaxed mb-8">Create your account to access curated study plans, live classes, tests, and progress tracking for your target exam.</p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 text-sm font-semibold text-white/90">
                            <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"></path></svg>
                            Personalized preparation roadmap
                        </div>
                        <div class="flex items-center gap-2 text-sm font-semibold text-white/90">
                            <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"></path></svg>
                            Live mentor-led classes
                        </div>
                        <div class="flex items-center gap-2 text-sm font-semibold text-white/90">
                            <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"></path></svg>
                            Smart test analytics
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 rounded-3xl p-[1px] bg-gradient-to-br from-slate-200 via-primary-200/70 to-primary-200/70">
                <div class="h-full rounded-3xl bg-white/92 backdrop-blur-md border border-white/80 p-6 sm:p-8 shadow-[0_14px_35px_rgba(15,23,42,0.10)]">
                    <div class="mb-7">
                        <h1 class="text-3xl font-black text-slate-900 mb-2">Join Topper's Hope</h1>
                        <p class="text-slate-500 text-sm">Start your journey towards academic excellence</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
                            <ul class="text-sm text-red-600 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Full Name</label>
                                <input id="name" name="name" type="text" required value="{{ old('name') }}"
                                    class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all sm:text-sm font-medium" placeholder="Rahul Sharma">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-bold text-slate-700 mb-1.5">Email Address</label>
                                <input id="email" name="email" type="email" required value="{{ old('email') }}"
                                    class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all sm:text-sm font-medium" placeholder="rahul@example.com">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-bold text-slate-700 mb-1.5">Phone Number</label>
                                <input id="phone" name="phone" type="tel" required value="{{ old('phone') }}"
                                    class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all sm:text-sm font-medium" placeholder="{{ config('contact.phone') }}">
                            </div>

                            <div>
                                <label for="dob" class="block text-sm font-bold text-slate-700 mb-1.5">Date of Birth</label>
                                <input id="dob" name="dob" type="date" required value="{{ old('dob') }}"
                                    class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all sm:text-sm font-medium">
                            </div>

                            <div class="md:col-span-2">
                                <x-category-fields
                                    :category-id="old('category_id')"
                                    :subcategory-id="old('subcategory_id')"
                                    :subcategory-required="true"
                                />
                            </div>

                            <div>
                                <label for="state" class="block text-sm font-bold text-slate-700 mb-1.5">State</label>
                                <input id="state" name="state" type="text" required value="{{ old('state') }}"
                                    class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all sm:text-sm font-medium" placeholder="Maharashtra">
                            </div>

                            <div>
                                <label for="district" class="block text-sm font-bold text-slate-700 mb-1.5">District / City</label>
                                <input id="district" name="district" type="text" required value="{{ old('district') }}"
                                    class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all sm:text-sm font-medium" placeholder="Mumbai">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">Create Password</label>
                                <input id="password" name="password" type="password" required
                                    class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all sm:text-sm font-medium" placeholder="••••••••">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-1.5">Confirm Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                    class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all sm:text-sm font-medium" placeholder="••••••••">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-[0_10px_25px_rgba(79,70,229,0.30)] text-sm font-bold text-white bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all">
                                Create Account & Secure Spot
                            </button>
                        </div>
                        
                        <div class="text-center mt-6 border-t border-slate-100 pt-6">
                            <p class="text-sm text-slate-500 font-medium">
                                Already enrolled? 
                                <a href="{{ route('login') }}" class="font-bold text-primary hover:text-primary-700 transition-colors">Sign in here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
