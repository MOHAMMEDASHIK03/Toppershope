@extends('admin.layouts.app')
@section('title', 'Academic Overview')
@section('page_title', 'Courses & Batches')

@section('content')
<x-admin.page-header title="Academic offerings" subtitle="Create and configure master courses and batches from the admin hub.">
    <x-slot:actions>
        <a href="{{ route('admin.courses.create') }}" class="btn-primary">
            <i class="ph ph-plus"></i> New master course
        </a>
    </x-slot:actions>
</x-admin.page-header>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <x-admin.card title="Active courses" :padding="false">
        <div class="panel-table-wrap">
            <table class="admin-table w-full text-left">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>
                                <span class="font-semibold text-slate-800">{{ $course->name }}</span>
                                @if(!$course->is_published)
                                    <span class="ml-2 px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200">Draft</span>
                                @endif
                            </td>
                            <td class="text-slate-600">{{ $course->category?->name ?? '—' }}</td>
                            <td class="text-right whitespace-nowrap">
                                <a href="{{ route('admin.courses.edit', $course->id) }}" class="text-primary-700 hover:text-primary-800 font-semibold text-sm mr-2">Edit</a>
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-semibold">{{ $course->batches_count }} batches</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-10 text-center">
                                <p class="text-slate-500 text-sm mb-4">No courses published yet.</p>
                                <a href="{{ route('admin.courses.create') }}" class="btn-primary text-sm inline-flex">
                                    <i class="ph ph-plus"></i> Create first course
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>

    <x-admin.card :padding="false">
        <x-slot:header>
            <h3 class="font-semibold text-slate-900">Latest batches</h3>
            <span class="text-xs font-semibold uppercase text-slate-500">Top 10</span>
        </x-slot:header>
        <div class="panel-table-wrap">
            <table class="admin-table w-full text-left">
                <thead>
                    <tr>
                        <th>Batch</th>
                        <th>Status</th>
                        <th class="text-right">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $batch)
                        <tr>
                            <td>
                                <span class="font-semibold text-slate-800 block">{{ $batch->name }}</span>
                                <span class="text-xs text-slate-500">{{ $batch->course?->name ?? 'Unlinked course' }}</span>
                            </td>
                            <td>
                                @if($batch->is_upcoming)
                                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-amber-50 text-amber-700">Upcoming</span>
                                @elseif($batch->status === 'filling_fast')
                                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-rose-50 text-rose-700">Filling fast</span>
                                @else
                                    <span class="badge-active">Active</span>
                                @endif
                            </td>
                            <td class="text-right font-semibold text-slate-800">₹{{ number_format($batch->price) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-slate-500 py-8">No upcoming or active batches.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>
</div>
@endsection
