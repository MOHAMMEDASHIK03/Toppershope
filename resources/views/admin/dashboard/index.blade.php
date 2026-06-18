@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page_title', 'Admin Dashboard')

@section('content')
<x-admin.page-header title="Admin Dashboard" subtitle="Overview of platform health, revenue, and recent activity across Topper's Hope." />

{{-- Ecosystem snapshot --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <x-admin.stat-card label="Active students" :value="number_format($totalStudents)" accent="indigo" />
    <x-admin.stat-card label="Active employees" :value="number_format($totalEmployees)" accent="emerald" />
    <x-admin.stat-card label="Live ad campaigns" :value="number_format($totalAdCampaigns)" accent="sky" />
    <x-admin.stat-card label="Published courses" :value="number_format($activeCourses)" accent="violet" />
</div>

{{-- Financial overview --}}
<div class="mb-2">
    <h2 class="text-sm font-semibold text-slate-700">Financial overview</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <x-admin.stat-card label="Total revenue (all time)" :value="'₹' . number_format($totalIncome, 2)" accent="emerald"
        :trend="($incomeGrowth >= 0 ? '+' : '') . number_format($incomeGrowth, 1) . '% vs last month'"
        :trend-up="$incomeGrowth >= 0" />
    <x-admin.stat-card label="Total payroll (expenses)" :value="'₹' . number_format($totalPayroll, 2)" accent="rose"
        :trend="($expenseGrowth >= 0 ? '+' : '') . number_format($expenseGrowth, 1) . '% vs last month'"
        :trend-up="false" />
    <x-admin.stat-card label="Net margin (P&L)" :value="'₹' . number_format($netMargin, 2)" :accent="$netMargin >= 0 ? 'emerald' : 'rose'"
        hint="Overall ecosystem balance" />
</div>

{{-- Recent transactions --}}
<x-admin.card>
    <x-slot:header>
        <h3 class="font-semibold text-slate-900">Recent financial events</h3>
        <select class="text-sm border border-slate-200 rounded-lg px-3 py-1.5 text-slate-700 bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-indigo-500 outline-none">
            <option>All transactions</option>
            <option>Course sales only</option>
            <option>Payroll only</option>
            <option>Manual expenses</option>
        </select>
    </x-slot:header>

    <form action="{{ route('admin.expenses.store') }}" method="POST" class="mb-6 p-4 bg-slate-50 border border-slate-200 rounded-xl">
        @csrf
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Quick log expense</p>
        <div class="flex flex-col md:flex-row gap-3 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-medium text-slate-600 mb-1">Title</label>
                <input type="text" name="title" required placeholder="e.g. Server hosting"
                    class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-orange-500/20 focus:border-indigo-500 outline-none">
            </div>
            <div class="w-full md:w-32">
                <label class="block text-xs font-medium text-slate-600 mb-1">Amount (₹)</label>
                <input type="number" step="0.01" name="amount" required placeholder="0.00"
                    class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-orange-500/20 focus:border-indigo-500 outline-none">
            </div>
            <div class="w-full md:w-40">
                <label class="block text-xs font-medium text-slate-600 mb-1">Date</label>
                <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}"
                    class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-orange-500/20 focus:border-indigo-500 outline-none">
            </div>
            <button type="submit" class="btn-primary h-[38px] w-full md:w-auto whitespace-nowrap">
                <i class="ph ph-plus"></i> Log expense
            </button>
        </div>
    </form>

    <div class="panel-table-wrap -mx-5 px-5">
        <table class="admin-table panel-table w-full text-left">
            <thead>
                <tr>
                    <th>Transaction</th>
                    <th>Party</th>
                    <th>Date</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions as $tx)
                <tr>
                    <td>
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $tx->type === 'income' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                <i class="ph {{ $tx->type === 'income' ? 'ph-arrow-down-left' : 'ph-arrow-up-right' }}"></i>
                            </span>
                            <div>
                                <span class="font-medium text-slate-800 block">{{ $tx->source }}</span>
                                <span class="text-xs text-slate-500 capitalize">{{ $tx->type }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="text-slate-600">{{ $tx->user }}</td>
                    <td class="text-slate-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($tx->date)->format('M d, Y') }}</td>
                    <td class="text-right font-semibold whitespace-nowrap {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $tx->type === 'income' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <x-admin.empty-state title="No financial data yet" description="Transactions will appear here once recorded." />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin.card>
@endsection
