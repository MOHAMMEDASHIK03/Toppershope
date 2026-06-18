@extends('layouts.faculty')

@section('title', 'Master Courses')
@section('page_header', 'Master Courses')

@section('content')
<div x-data="courseManager()" @keydown.escape.window="closeModal()">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800">Master Course Library</h2>
        </div>
        <button type="button" @click="openCreateModal()" class="btn-primary text-sm py-2.5 px-4 rounded-xl font-semibold shadow-sm h-fit">
            <i class="ph-bold ph-plus text-lg"></i>
            Add new course
        </button>
    </div>

    @if($courses->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <i class="ph-fill ph-books text-2xl text-slate-400"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">No master courses yet</h3>
            <p class="text-slate-500 max-w-md mx-auto mb-6">Create your first master course to begin structuring your curriculum and batches.</p>
            <button type="button" @click="openCreateModal()" class="btn-primary px-6 py-2.5 rounded-xl font-semibold shadow-sm">
                Create first course
            </button>
        </div>
    @else
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="panel-table-wrap">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6">Course Name</th>
                            <th class="py-4 px-6 text-center">Category</th>
                            <th class="py-4 px-6 text-center">Subcategory</th>
                            <th class="py-4 px-6 text-center">Structure</th>
                            <th class="py-4 px-6 text-center">Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($courses as $course)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-primary-50 border border-primary-100 flex items-center justify-center shrink-0">
                                            <i class="ph-fill ph-book-open-text text-xl text-primary"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $course->title ?? $course->name }}</p>
                                            <p class="text-xs text-slate-500 max-w-[200px] truncate">{{ Str::limit($course->description, 50) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $course->category?->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-primary-50 text-primary-800 border border-primary-200">
                                        {{ $course->subcategory?->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-4 text-xs font-medium text-slate-500">
                                        <div class="flex flex-col items-center">
                                            <span class="text-slate-800 font-bold">{{ $course->subjects_count }}</span>
                                            <span class="text-[10px] uppercase">Subjects</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="text-slate-800 font-bold">{{ $course->batches_count }}</span>
                                            <span class="text-[10px] uppercase">Batches</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($course->is_published)
                                        <span class="bg-primary-50 text-primary-700 border border-primary-200 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide">Published</span>
                                    @else
                                        <span class="bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide">Draft</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('faculty.head.courses.edit', $course->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary-700 hover:bg-primary-50 transition-colors" title="Manage Master Course">
                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                        </a>
                                        <form action="{{ route('faculty.head.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course? This action will cascade deletions.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete Course">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Create Master Course Modal --}}
    <template x-teleport="body">
        <div
            x-show="showModal"
            x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-title"
        >
            {{-- Backdrop --}}
            <div
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
                x-show="showModal"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="closeModal()"
            ></div>

            {{-- Panel --}}
            <div
                class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[min(90vh,720px)]"
                x-show="showModal"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                @click.stop
            >
                {{-- Header --}}
                <div class="flex items-start justify-between gap-4 px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white shrink-0">
                    <div>
                        <h3 id="modal-title" class="text-lg font-black text-slate-800 leading-tight">Create Master Course</h3>
                    </div>
                    <button
                        type="button"
                        @click="closeModal()"
                        class="shrink-0 w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors"
                        aria-label="Close"
                    >
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('faculty.head.courses.store') }}" method="POST" class="flex flex-col min-h-0 flex-1">
                    @csrf

                    <div class="px-6 py-5 space-y-5 overflow-y-auto flex-1">
                        <x-forms.master-course-fields :categories="$categories" />
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                        <button
                            type="button"
                            @click="closeModal()"
                            class="px-4 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors"
                        >
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="ph-bold ph-plus-circle"></i>
                            Create course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('courseManager', () => ({
            showModal: false,

            openCreateModal() {
                this.showModal = true;
                document.body.classList.add('overflow-hidden');
                this.$nextTick(() => window.initCategoryFields?.());
            },

            closeModal() {
                this.showModal = false;
                document.body.classList.remove('overflow-hidden');
            },
        }));
    });
</script>
@endpush
@endsection
