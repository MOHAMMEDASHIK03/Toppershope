@extends('hr.layouts.app')
@section('title', isset($jobApplication) ? 'Review Application' : 'Manual Entry')
@section('page_title', 'Recruitment')

@section('content')
<div class="max-w-6xl">
    <x-create-page-header
        :back-href="route('hr.job-applications.index')"
        back-label="Back to applications"
        :title="isset($jobApplication) ? 'Applicant profile' : 'New candidate'"
        subtitle="Record or review a job application."
    />

    @if(isset($jobApplication))
        <div class="mb-6 bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-100 rounded-2xl p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-[10px] font-bold uppercase text-orange-600 tracking-widest mb-1">Candidacy for</p>
                <p class="font-bold text-slate-900 text-lg">{{ $jobApplication->jobPosting?->title ?? 'N/A' }}</p>
                <p class="text-sm text-slate-600 capitalize">{{ str_replace('-', ' ', $jobApplication->jobPosting?->employment_type ?? '—') }} · {{ $jobApplication->jobPosting?->location ?? '—' }}</p>
            </div>
            <div class="sm:text-right shrink-0">
                <p class="text-[10px] font-bold uppercase text-slate-400 tracking-widest mb-1">Applied</p>
                <p class="font-bold text-slate-800">{{ $jobApplication->created_at->format('M d, Y') }}</p>
                <p class="text-xs text-orange-600 font-medium">{{ $jobApplication->created_at->diffForHumans() }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h3 class="text-xs font-bold uppercase tracking-wide text-orange-600 mb-5 pb-3 border-b border-slate-100">Candidate information</h3>

                <form id="app-form" action="{{ isset($jobApplication) ? route('hr.job-applications.update', $jobApplication) : route('hr.job-applications.store') }}" method="POST">
                    @csrf
                    @if(isset($jobApplication))
                        @method('PUT')
                    @endif

                    <div class="space-y-6">
                        @if(!isset($jobApplication))
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Applying for <span class="text-red-500">*</span></label>
                            <select name="job_posting_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="">Select role…</option>
                                @foreach(\App\Models\HR\JobPosting::where('status', 'open')->orderBy('title')->get() as $post)
                                    <option value="{{ $post->id }}">{{ $post->title }} ({{ $post->employment_type }})</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="applicant_name" value="{{ old('applicant_name', $jobApplication->applicant_name ?? '') }}" required {{ isset($jobApplication) ? 'readonly' : '' }}
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none transition-all font-medium text-slate-900">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Email Contact <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $jobApplication->email ?? '') }}" required {{ isset($jobApplication) ? 'readonly' : '' }}
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none transition-all font-medium text-slate-900">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone', $jobApplication->phone ?? '') }}" required {{ isset($jobApplication) ? 'readonly' : '' }}
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none transition-all font-medium text-slate-900">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Cover Letter / Pitch</label>
                                <textarea name="cover_letter" rows="5" {{ isset($jobApplication) ? 'readonly' : '' }}
                                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none transition-all font-medium text-slate-900 placeholder:text-slate-400">{{ old('cover_letter', $jobApplication->cover_letter ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Status Modifiers if editing -->
                        @if(isset($jobApplication))
                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <label class="block text-sm font-bold text-slate-700 mb-3">Pipeline Status Move</label>
                            
                            @php $st = old('status', $jobApplication->status); @endphp
                            
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $statuses = [
                                        'applied' => ['lbl' => 'Applied', 'col' => 'text-blue-700 focus:ring-blue-600', 'bg' => 'hover:border-blue-300'],
                                        'shortlisted' => ['lbl' => 'Shortlisted', 'col' => 'text-purple-700 focus:ring-purple-600', 'bg' => 'hover:border-purple-300'],
                                        'interviewed' => ['lbl' => 'Interviewed', 'col' => 'text-indigo-700 focus:ring-indigo-600', 'bg' => 'hover:border-indigo-300'],
                                        'offered' => ['lbl' => 'Offered', 'col' => 'text-amber-700 focus:ring-amber-600', 'bg' => 'hover:border-amber-300'],
                                        'hired' => ['lbl' => 'Hired', 'col' => 'text-emerald-700 focus:ring-emerald-600', 'bg' => 'hover:border-emerald-300'],
                                        'rejected' => ['lbl' => 'Rejected', 'col' => 'text-rose-700 focus:ring-rose-600', 'bg' => 'hover:border-rose-300'],
                                    ];
                                @endphp
                                
                                @foreach($statuses as $val => $data)
                                <label class="panel-radio-card">
                                    <input type="radio" name="status" value="{{ $val }}" class="panel-radio-card__input" {{ $st == $val ? 'checked' : '' }}>
                                    <span class="panel-radio-card__face text-xs px-3 py-2">{{ $data['lbl'] }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    </div>

                    <div class="pt-6 mt-8 border-t border-slate-100">
                        <button type="submit" class="px-6 py-2.5 btn-primary font-bold rounded-xl text-sm">
                            {{ isset($jobApplication) ? 'Save pipeline status' : 'Register applicant' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($jobApplication))
        <div class="space-y-6 xl:sticky xl:top-6">
            <div class="bg-gradient-to-br from-orange-500 to-amber-500 rounded-2xl p-6 text-white shadow-md">
                <p class="text-[10px] uppercase font-bold tracking-widest text-orange-100 mb-1">Candidate file</p>
                <h3 class="font-bold text-lg mb-4">Resume / CV</h3>
                @if($jobApplication->resume_path)
                    <a href="{{ Storage::url($jobApplication->resume_path) }}" target="_blank" rel="noopener" class="w-full flex items-center justify-center gap-2 py-3 bg-white text-orange-700 font-bold rounded-xl hover:bg-orange-50 transition-colors text-sm">
                        View / download resume
                    </a>
                @else
                    <p class="text-sm text-orange-100/90 py-3 text-center bg-white/10 rounded-xl">No resume uploaded</p>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-xs font-bold uppercase tracking-wide text-slate-800 mb-4">Interviews</h3>

                <div class="space-y-3 mb-5">
                    @forelse($jobApplication->interviews as $interview)
                        <div class="border border-slate-200 bg-slate-50 rounded-xl p-4">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold uppercase text-orange-600 mb-0.5">Round {{ $loop->iteration }}</p>
                                    <p class="font-bold text-slate-800 text-sm">{{ $interview->formattedSchedule() }}</p>
                                </div>
                                <form action="{{ route('hr.interviews.update', $interview) }}" method="POST" class="shrink-0">
                                    @csrf @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg px-2 py-1">
                                        <option value="scheduled" {{ $interview->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="completed" {{ $interview->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $interview->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="no_show" {{ $interview->status === 'no_show' ? 'selected' : '' }}>No show</option>
                                    </select>
                                </form>
                            </div>
                            @if($interview->location_or_link)
                                <a href="{{ $interview->location_or_link }}" target="_blank" rel="noopener" class="text-xs font-semibold text-orange-600 hover:underline block truncate mb-1">Meeting link ↗</a>
                            @endif
                            @if($interview->feedback)
                                <p class="text-xs text-slate-500 line-clamp-2">{{ $interview->feedback }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs font-medium text-slate-400 text-center py-6 bg-slate-50 border border-dashed border-slate-200 rounded-xl">No interviews scheduled yet.</p>
                    @endforelse
                </div>

                <form action="{{ route('hr.interviews.store') }}" method="POST" class="rounded-xl border border-orange-200 bg-orange-50/60 p-4">
                    @csrf
                    <input type="hidden" name="job_application_id" value="{{ $jobApplication->id }}">
                    <p class="text-xs font-bold uppercase text-orange-800 mb-3 tracking-wide">Schedule new session</p>

                    <div class="space-y-3">
                        <div>
                            <label for="interview_date" class="block text-xs font-semibold text-slate-700 mb-1">Interview date <span class="text-red-500">*</span></label>
                            <input
                                type="date"
                                id="interview_date"
                                name="interview_date"
                                value="{{ old('interview_date', now()->addDay()->format('Y-m-d')) }}"
                                required
                                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 th-picker-source"
                            >
                        </div>

                        <details class="group">
                            <summary class="text-xs font-semibold text-orange-700 cursor-pointer list-none flex items-center gap-1">
                                <span class="group-open:rotate-90 transition-transform">›</span>
                                Add specific time (optional)
                            </summary>
                            <div class="mt-2">
                                <input
                                    type="time"
                                    name="interview_time"
                                    value="{{ old('interview_time') }}"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900"
                                >
                            </div>
                        </details>

                        <div>
                            <label for="meeting_link" class="block text-xs font-semibold text-slate-700 mb-1">Meeting link</label>
                            <input
                                type="url"
                                id="meeting_link"
                                name="meeting_link"
                                value="{{ old('meeting_link') }}"
                                placeholder="https://meet.google.com/…"
                                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400"
                            >
                        </div>

                        <button type="submit" class="w-full py-2.5 btn-primary font-bold rounded-xl text-sm">
                            Book interview
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
