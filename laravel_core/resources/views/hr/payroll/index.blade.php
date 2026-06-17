@extends('hr.layouts.app')
@section('title', 'Payroll Management')
@section('page_title', 'Payroll')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Payroll Records</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Review generated payslips for a selected month</p>
    </div>

    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <form action="{{ route('hr.payroll.index') }}" method="GET" class="flex gap-2 flex-1 md:flex-none min-w-[10rem]">
            <input type="month" name="month_year_picker" value="{{ \Carbon\Carbon::createFromFormat('m-Y', $monthYear)->format('Y-m') }}"
                   onchange="let v=this.value.split('-'); document.getElementById('myhidden').value=v[1]+'-'+v[0]; this.form.submit();"
                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none font-medium text-slate-700 text-sm shadow-sm">
            <input type="hidden" name="month_year" id="myhidden" value="{{ $monthYear }}">
        </form>

        <div class="bg-slate-200 w-px h-8 hidden md:block shrink-0"></div>

        <a href="{{ route('hr.payroll.settings') }}" class="p-2.5 bg-white border border-slate-200 text-slate-600 font-semibold rounded-xl text-sm shadow-sm hover:bg-slate-50 transition-colors shrink-0" title="Salary Settings">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160Zm88-29.84q.06-2.16,0-4.32l14.92-18.64a8,8,0,0,0,1.48-7.06,107.21,107.21,0,0,0-10.88-26.25,8,8,0,0,0-6-3.93l-23.72-2.64q-1.48-1.56-3-3L186,40.54a8,8,0,0,0-3.94-6,107.71,107.71,0,0,0-26.25-10.87,8,8,0,0,0-7.06,1.49L130.16,40Q128,40,125.84,40L107.2,25.11a8,8,0,0,0-7.06-1.48A107.6,107.6,0,0,0,73.89,34.51a8,8,0,0,0-3.93,6L67.32,64.27q-1.56,1.49-3,3L40.54,69.94a8,8,0,0,0-6,3.94,107.71,107.71,0,0,0-10.87,26.25,8,8,0,0,0,1.49,7.06L40,125.84Q40,128,40,130.16L25.11,148.8a8,8,0,0,0-1.48,7.06,107.21,107.21,0,0,0,10.88,26.25,8,8,0,0,0,6,3.93l23.72,2.64q1.49,1.56,3,3L69.94,215.46a8,8,0,0,0,3.94,6,107.71,107.71,0,0,0,26.25,10.87,8,8,0,0,0,7.06-1.49L125.84,216q2.16.06,4.32,0l18.64,14.92a8,8,0,0,0,7.06,1.48,107.21,107.21,0,0,0,26.25-10.88,8,8,0,0,0,3.93-6l2.64-23.72q1.56-1.48,3-3L215.46,186a8,8,0,0,0,6-3.94,107.71,107.71,0,0,0,10.87-26.25,8,8,0,0,0-1.49-7.06Zm-16.1-6.51.92,4.62q-.53,5.36-2.58,10.28l-1.8,4.31,17.11,21.36a91.73,91.73,0,0,1-6.93,16.7L189,178l-4,2.37A61.44,61.44,0,0,1,174,188.16l-4.52.79-3.2,27.18a91.3,91.3,0,0,1-16.69,6.92l-17-21.31-4.7,1.15q-5,1.2-10.39,1.2t-10.39-1.2l-4.7-1.15-17,21.31a91.3,91.3,0,0,1-16.69-6.92l-3.2-27.18-4.52-.79a61.44,61.44,0,0,1-11-7.79l-4-2.37-27.56,3.1a91.73,91.73,0,0,1-6.93-16.7L56.46,143l-1.8-4.31q-2-4.9-2.58-10.28l-.92-4.62L34,102.48A91.73,91.73,0,0,1,40.94,85.78l27.56-3.1,4-2.37a61.44,61.44,0,0,1,11-7.79l4.52-.79,3.2-27.18a91.3,91.3,0,0,1,16.69-6.92l17,21.31,4.7-1.15q5-1.2,10.39-1.2t10.39,1.2l4.7,1.15,17-21.31a91.3,91.3,0,0,1,16.69,6.92l3.2,27.18,4.52.79a61.44,61.44,0,0,1,11,7.79l4,2.37,27.56-3.1a91.73,91.73,0,0,1,6.93,16.7L199.54,113l1.8,4.31Q203.38,122.2,204,127.59Z"/></svg>
        </a>

        <a href="{{ route('hr.payroll.create') }}" class="px-4 py-2.5 btn-primary font-semibold rounded-xl text-sm shadow-sm hover:bg-orange-600 focus:ring-4 focus:ring-orange-100 transition-colors inline-flex items-center gap-2 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z"/></svg>
            Generate Payroll
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="panel-list panel-list--payroll">
        <div class="panel-list__head">
            <span>Employee</span>
            <span class="panel-list__cell--money">Basic</span>
            <span class="panel-list__cell--money">Allowances</span>
            <span class="panel-list__cell--money">Deductions</span>
            <span class="panel-list__cell--money">Net payable</span>
            <span>Status</span>
            <span class="text-right">Actions</span>
        </div>

        @forelse($payrolls as $pr)
            <article class="panel-list__row">
                <div class="min-w-0">
                    <span class="panel-list__cell-label">Employee</span>
                    <x-hr.panel-list-employee :employee="$pr->employee" />
                </div>
                <div>
                    <span class="panel-list__cell-label">Basic salary</span>
                    <p class="panel-list__cell--money">₹{{ number_format($pr->basic_salary, 2) }}</p>
                </div>
                <div>
                    <span class="panel-list__cell-label">Allowances</span>
                    <p class="panel-list__cell--money is-positive">+ ₹{{ number_format($pr->allowances, 2) }}</p>
                </div>
                <div>
                    <span class="panel-list__cell-label">Deductions</span>
                    <p class="panel-list__cell--money is-negative">− ₹{{ number_format($pr->deductions, 2) }}</p>
                </div>
                <div class="panel-list__cell--net-pay">
                    <span class="panel-list__cell-label">Net payable</span>
                    <p class="panel-list__cell--money is-net">₹{{ number_format($pr->net_payable, 2) }}</p>
                </div>
                <div class="panel-list__cell--status">
                    <span class="panel-list__cell-label">Status</span>
                    @if($pr->status === 'paid')
                        <span class="panel-list-status-pill bg-emerald-50 border border-emerald-100 text-emerald-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span> Paid
                        </span>
                    @else
                        <span class="panel-list-status-pill bg-amber-50 border border-amber-100 text-amber-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span> Pending
                        </span>
                    @endif
                </div>
                <div class="panel-list__cell--end">
                    <span class="panel-list__cell-label">Actions</span>
                    <x-hr.panel-list-actions :edit-route="route('hr.payroll.show', $pr->id)" edit-label="View Slip" variant="view" />
                </div>
            </article>
        @empty
            <div class="px-6 py-12 text-center text-slate-500 font-medium text-sm">
                No payroll records found for this period.<br>
                <a href="{{ route('hr.payroll.create') }}" class="text-orange-600 hover:underline mt-2 inline-block">Generate payrolls now</a>
            </div>
        @endforelse
    </div>

    <x-panel.pagination :paginator="$payrolls" />
</div>
@endsection
