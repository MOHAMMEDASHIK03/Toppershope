@extends('student.layouts.app')
@section('title', 'Profile')
@section('page_title', 'My Profile')

@section('content')
@php
    $targetExamKey = strtolower((string) ($user->target_exam ?? ''));
    $targetExamOptions = [
        '' => 'Select preference',
        'jee' => 'JEE',
        'neet' => 'NEET',
        'foundation' => 'Foundation',
        'boards' => 'Boards',
        'coding' => 'Coding / Python',
    ];
@endphp
<div class="max-w-2xl">
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        {{-- Header --}}
        <div class="p-6 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-slate-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center text-2xl font-bold text-white shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-400">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('student.profile.update') }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-3 admin-input rounded-xl text-sm resize-none outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                @error('name') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Email (Read Only)</label>
                <input type="email" value="{{ $user->email }}" disabled
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-3 admin-input rounded-xl text-sm resize-none outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>

            <div class="relative z-10">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Target Exam</label>
                <select name="target_exam" class="admin-input w-full rounded-xl text-sm">
                    @foreach($targetExamOptions as $value => $label)
                        <option value="{{ $value }}" @selected($targetExamKey === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4">
                <button type="submit" class="px-6 py-3 rounded-xl btn-primary text-white font-bold text-sm  transition-all shadow-lg shadow-orange-500/10">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Account Info --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 mt-6">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Account Information</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-slate-500">Member Since</span>
                <span class="text-slate-600">{{ $user->created_at->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Account Status</span>
                <span class="text-emerald-400 font-bold">Active</span>
            </div>
        </div>
    </div>
</div>
@endsection
