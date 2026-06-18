@extends('hr.layouts.app')
@section('title', 'Documents Vault')
@section('page_title', 'Company')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Employee Documents</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Securely manage sensitive employee records</p>
    </div>
    
    <div class="flex items-center gap-3 w-full md:w-auto">
        <a href="{{ route('hr.employee-documents.create') }}" class="w-full md:w-auto px-4 py-2.5 btn-primary font-semibold rounded-xl text-sm shadow-sm hover:bg-primary-700 focus:ring-4 focus:ring-primary-100 transition-colors inline-flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,144v64a8,8,0,0,1-8,8H40a8,8,0,0,1-8-8V144a8,8,0,0,1,16,0v56H208V144a8,8,0,0,1,16,0ZM93.66,77.66,120,51.31V168a8,8,0,0,0,16,0V51.31l26.34,26.35a8,8,0,0,0,11.32-11.32l-40-40a8,8,0,0,0-11.32,0l-40,40A8,8,0,0,0,93.66,77.66Z"/></svg>
            Upload document
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <form method="GET" action="{{ route('hr.employee-documents.index') }}" class="p-4 border-b border-slate-100 bg-slate-50 flex flex-col sm:flex-row sm:items-center gap-3">
        <div class="relative flex-1 max-w-xl w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"/></svg>
            </div>
            <input
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search documents or employees..."
                class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-600/15 focus:border-primary-500 outline-none transition-all placeholder:text-slate-400 font-medium"
            >
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button type="submit" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold rounded-lg text-sm hover:bg-slate-50 transition-colors">
                Search
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('hr.employee-documents.index') }}" class="px-4 py-2 text-slate-500 font-semibold rounded-lg text-sm hover:text-slate-700 hover:bg-white transition-colors">
                    Clear
                </a>
            @endif
        </div>
    </form>

    <div class="panel-table-wrap">
        <table class="panel-table w-full text-left text-sm">
            <thead class="bg-white border-b border-slate-100 text-slate-400 font-black uppercase tracking-wider text-[10px]">
                <tr>
                    <th class="px-6 py-4">Document</th>
                    <th class="px-6 py-4">Employee</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Uploaded</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($documents as $doc)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-rose-50 text-primary-600 rounded-lg shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM160,51.31,188.69,80H160ZM200,216H56V40h88V88a8,8,0,0,0,8,8h48V216Zm-24-64a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h80A8,8,0,0,1,176,152Zm0-32a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h80A8,8,0,0,1,176,120Z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $doc->document_name }}</p>
                                <p class="text-xs font-medium text-slate-500 max-w-[150px] truncate" title="{{ $doc->file_path }}">{{ basename($doc->file_path) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-700">{{ $doc->employee->first_name }} {{ $doc->employee->last_name }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ $doc->employee->employee_id }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wide">
                            {{ $doc->category_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium text-xs">
                        {{ $doc->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center justify-end gap-2">
                            <a
                                href="{{ Storage::url($doc->file_path) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                download
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold tracking-wide text-primary-700 bg-white border border-primary-200 rounded-lg hover:bg-primary-50 transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,152v56a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V152a16,16,0,0,1,16-16H80a8,8,0,0,1,0,16H48v56H208V152H176a8,8,0,0,1,0-16h32A16,16,0,0,1,224,152ZM122.34,165.66l-26.35-26.34V216a8,8,0,0,1-16,0V139.31l-26.34,26.35a8,8,0,0,1-11.32-11.32l40-40a8,8,0,0,1,11.32,0l40,40A8,8,0,0,1,122.34,165.66Z"/></svg>
                                Download
                            </a>
                            <form
                                action="{{ route('hr.employee-documents.destroy', $doc) }}"
                                method="POST"
                                class="inline-flex"
                                data-confirm="Permanently delete this document from the vault?"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center p-1.5 text-primary-600 bg-white border border-rose-200 rounded-lg hover:bg-rose-50 transition-colors"
                                    title="Delete document"
                                    aria-label="Delete document"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-medium text-sm">
                        @if(request()->filled('search'))
                            No documents match “{{ request('search') }}”.<br>
                            <a href="{{ route('hr.employee-documents.index') }}" class="text-primary-700 hover:underline mt-2 inline-block">Clear search</a>
                        @else
                            No documents stored in the vault.<br>
                            <a href="{{ route('hr.employee-documents.create') }}" class="text-primary-700 hover:underline mt-2 inline-block">Upload the first document</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <x-panel.pagination :paginator="$documents" />
</div>
@endsection
