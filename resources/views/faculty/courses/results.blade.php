@extends('layouts.faculty')

@section('title', 'Results: ' . $course->name)

@section('page_header')
    <div class="flex items-center gap-3">
        <a href="{{ route('faculty.dashboard') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
            <i class="ph-bold ph-arrow-left"></i>
        </a>
        <span>Manage: <span class="text-primary">{{ $course->name }}</span></span>
    </div>
@endsection

@section('content')

<!-- Navbar specific to the Course -->
<div class="mb-8 border-b border-slate-200">
    <nav class="-mb-px flex space-x-8">
        <a href="{{ route('faculty.courses.curriculum', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Curriculum Builder
        </a>
        <a href="{{ route('faculty.courses.content.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Content Manager
        </a>
        <a href="{{ route('faculty.courses.quizzes.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Quizzes
        </a>
        <a href="{{ route('faculty.courses.doubts.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
            Doubts 
            @php $unresolvedCount = $course->doubts()->where('is_resolved', false)->count(); @endphp
            @if($unresolvedCount > 0)
                <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold">{{ $unresolvedCount }}</span>
            @endif
        </a>
        <a href="{{ route('faculty.courses.students.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Students
        </a>
        <a href="{{ route('faculty.courses.results.index', $course->id) }}" class="border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm">
            Results
        </a>
    </nav>
</div>

<!-- Results Data Table & Review Interface -->
<div x-data="{ 
    reviewingAttempt: null,
    attemptStudent: '',
    attemptScore: 0,
    attemptRemarks: '',
    attemptUrl: '',
    openReviewModal(attemptId, studentName, score, remarks, url) {
        this.reviewingAttempt = attemptId;
        this.attemptStudent = studentName;
        this.attemptScore = score;
        this.attemptRemarks = remarks || '';
        this.attemptUrl = url;
    }
}">
    
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Student Quiz Submissions</h3>
                <p class="text-sm text-slate-500">Review scores and provide manual remarks for the physics assessments.</p>
            </div>
            <div class="relative w-full sm:w-64">
                <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" placeholder="Search students..." class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2 text-sm focus:outline-none focus:border-primary-600 focus:ring-1 focus:ring-primary-500 transition-colors">
            </div>
        </div>

        <div class="panel-table-wrap">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <th class="py-3 px-6 text-center w-16">#</th>
                        <th class="py-3 px-6">Student</th>
                        <th class="py-3 px-6">Quiz Title</th>
                        <th class="py-3 px-6 text-center">Score</th>
                        <th class="py-3 px-6 text-center">Submitted At</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($attempts as $index => $attempt)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-xs">
                                        {{ substr($attempt->student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $attempt->student->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $attempt->student->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-700 font-medium">
                                {{ $attempt->quiz->title }}
                            </td>
                            <td class="py-4 px-6 text-center font-black {{ $attempt->score < 0 ? 'text-red-500' : ($attempt->score > 15 ? 'text-primary-500' : 'text-slate-700') }}">
                                {{ number_format($attempt->score, 2) }}
                            </td>
                            <td class="py-4 px-6 text-center text-slate-500 text-xs">
                                {{ $attempt->created_at->format('M j, Y g:i A') }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($attempt->faculty_remarks)
                                    <span class="bg-primary-50 text-primary-700 border border-primary-200 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide">Reviewed</span>
                                @else
                                    <span class="bg-slate-100 text-slate-600 border border-slate-200 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide">Pending</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <button @click="openReviewModal({{ $attempt->id }}, '{{ addslashes($attempt->student->name) }}', {{ $attempt->score }}, '{{ addslashes($attempt->faculty_remarks) }}', '{{ route('faculty.courses.results.remarks.update', [$course->id, $attempt->id]) }}')" class="bg-white border border-slate-200 hover:border-primary-300 hover:text-primary-700 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 shadow-sm">
                                    <i class="ph-bold {{ $attempt->faculty_remarks ? 'ph-pencil-simple' : 'ph-eye' }}"></i> 
                                    {{ $attempt->faculty_remarks ? 'Edit Remark' : 'Review' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="ph-fill ph-clipboard-x text-4xl text-slate-300 mb-3 block"></i>
                                    <span class="font-medium text-slate-500">No quiz attempts found.</span>
                                    <p class="text-xs mt-1">Students haven't submitted any quizzes for this course yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Alpine JS Modal for Applying Remarks -->
    <div x-show="reviewingAttempt !== null" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div x-show="reviewingAttempt !== null" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="reviewingAttempt = null" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="reviewingAttempt !== null" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                
                <form :action="attemptUrl" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-slate-100">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="ph-fill ph-student text-primary-700 text-xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-slate-800" id="modal-title">
                                    Reviewing <span x-text="attemptStudent" class="text-primary-700"></span>
                                </h3>
                                <div class="mt-1 mb-4 flex items-center gap-2">
                                    <span class="text-xs text-slate-500 font-medium">Achieved Score:</span>
                                    <span class="px-2 py-0.5 rounded text-xs font-black bg-slate-100 text-slate-700" x-text="attemptScore.toFixed(2)"></span>
                                </div>
                                
                                <div class="mt-2 text-left">
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Faculty Remarks</label>
                                    <textarea name="faculty_remarks" x-model="attemptRemarks" rows="4" class="w-full border border-slate-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-600 outline-none transition-all placeholder:text-slate-400" placeholder="Enter constructive feedback or remarks for the student..." required></textarea>
                                    <p class="text-[10px] text-slate-500 mt-2"><i class="ph-bold ph-info"></i> These remarks will be visible to the student on their graphical performance report.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <button type="button" @click="reviewingAttempt = null" class="w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:w-auto">
                            Cancel
                        </button>
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2 bg-primary-500 text-sm font-bold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:w-auto flex items-center gap-2">
                            <i class="ph-bold ph-floppy-disk"></i> Save Remarks
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
