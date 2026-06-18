@extends('layouts.admission')
@section('title', 'Active Trials')
@section('page_title', 'Trials Overview')

@section('content')
<div class="pt-2 space-y-4">

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 flex items-center justify-between border-b border-slate-100">
        <p class="text-sm font-semibold text-slate-500">{{ $trials->total() }} recorded trials</p>
    </div>

    @if($trials->isEmpty())
    <div class="px-6 py-16 text-center">
        <p class="text-3xl mb-3">🧪</p>
        <p class="text-slate-500 text-sm font-semibold">No trials have been issued yet.</p>
        <p class="text-slate-400 text-xs mt-1">Go to a contact's detail page to issue a trial.</p>
    </div>
    @else
    <div class="panel-table-wrap">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="px-6 py-3.5 text-left text-xs font-black text-slate-500 uppercase tracking-wide">Student</th>
                    <th class="px-6 py-3.5 text-left text-xs font-black text-slate-500 uppercase tracking-wide">Trial Credentials</th>
                    <th class="px-6 py-3.5 text-left text-xs font-black text-slate-500 uppercase tracking-wide">Batch Accessed</th>
                    <th class="px-6 py-3.5 text-left text-xs font-black text-slate-500 uppercase tracking-wide">Status / Expiry</th>
                    <th class="px-6 py-3.5 text-left text-xs font-black text-slate-500 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($trials as $trial)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-xs text-white shrink-0"
                                 style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                                {{ strtoupper(substr($trial->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 leading-tight">
                                    {{ $trial->name }}
                                    @if($trial->contact)
                                    <a href="{{ route('admission.contacts.show', $trial->contact) }}" class="text-primary-500 hover:underline">↗</a>
                                    @endif
                                </p>
                                <p class="text-xs text-slate-400 font-mono">{{ $trial->phone ?? $trial->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-mono text-xs font-bold text-slate-700">{{ $trial->trial_email }}</p>
                        <p class="text-[10px] text-slate-400">Issued by {{ $trial->issuedBy?->name ?? 'System' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @if($trial->batch)
                        <span class="px-2.5 py-1 text-xs font-bold bg-slate-100 text-slate-700 rounded-lg">{{ $trial->batch->name }}</span>
                        @else
                        <span class="text-slate-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($trial->is_expired || $trial->expires_at->isPast())
                        <div class="flex flex-col gap-1 items-start">
                            <span class="px-2 py-0.5 text-xs font-bold bg-red-100 text-red-600 rounded-full">Expired</span>
                            <span class="text-xs text-slate-400">{{ $trial->expires_at->format('d M Y') }}</span>
                        </div>
                        @else
                        <div class="flex flex-col gap-1 items-start">
                            <span class="px-2 py-0.5 text-xs font-bold bg-emerald-100 text-emerald-700 rounded-full">
                                {{ $trial->daysLeft() }}d left
                            </span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">{{ $trial->expires_at->format('d M, H:i') }}</span>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if(!$trial->is_expired && $trial->expires_at->isFuture())
                        <form method="POST" action="{{ route('admission.trials.expire', $trial) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Revoke this trial access immediately?')"
                                    class="text-xs font-bold text-red-500 hover:text-red-700 transition">Revoke</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-panel.pagination :paginator="$trials" />
    @endif
</div>
</div>
@endsection
