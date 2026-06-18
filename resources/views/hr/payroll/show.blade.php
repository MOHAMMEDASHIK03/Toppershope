@extends('hr.layouts.app')
@section('title', 'Payslip - ' . $payslip->employee['name'])
@section('page_title', 'Payroll')

@push('styles')
    @include('hr.payroll.partials.payslip-styles')
    <style>
        .payslip-toolbar {
            max-width: 210mm;
            margin: 0 auto 1rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }
        .payslip-toolbar__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .payslip-toolbar__btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #334155;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s;
        }
        .payslip-toolbar__btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }
        .payslip-toolbar__btn--primary {
            background: #1e3a8a;
            border-color: #1e3a8a;
            color: #fff;
        }
        .payslip-toolbar__btn--primary:hover {
            background: #1e40af;
            border-color: #1e40af;
            color: #fff;
        }
        .payslip-screen-wrap {
            max-width: 210mm;
            margin: 0 auto;
        }
    </style>
@endpush

@section('content')
<div class="payslip-print-root">
    <div class="no-print payslip-toolbar">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('hr.payroll.index') }}" class="payslip-toolbar__btn" title="Back">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"/></svg>
            </a>
            <div class="min-w-0">
                <h2 class="text-lg font-bold text-slate-800 truncate">Payslip — {{ $payslip->periodLabel }}</h2>
                <p class="text-xs text-slate-500 font-medium">{{ $payslip->employee['name'] }} · {{ $payslip->payslipNumber }}</p>
            </div>
        </div>
        <div class="payslip-toolbar__actions">
            <button type="button" class="payslip-toolbar__btn" onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256"><path d="M200,48V32a8,8,0,0,0-8-8H64a8,8,0,0,0-8,8V48a16,16,0,0,0-16,16V160a16,16,0,0,0,16,16H72v40a8,8,0,0,0,8,8H176a8,8,0,0,0,8-8V176h32a16,16,0,0,0,16-16V64A16,16,0,0,0,200,48Z"/></svg>
                Print
            </button>
            <a href="{{ route('hr.payroll.pdf', $payslip->payroll->id) }}" class="payslip-toolbar__btn payslip-toolbar__btn--primary" target="_blank" rel="noopener">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256"><path d="M224,152v56a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V152a16,16,0,0,1,16-16H80a8,8,0,0,1,0,16H48v56H208V152H176a8,8,0,0,1,0-16h32A16,16,0,0,1,224,152ZM93.66,77.66,120,51.31V128a8,8,0,0,0,16,0V51.31l26.34,26.35a8,8,0,0,0,11.32-11.32l-40-40a8,8,0,0,0-11.32,0l-40,40A8,8,0,0,0,93.66,77.66Z"/></svg>
                Download PDF
            </a>
        </div>
    </div>

    <div class="payslip-screen-wrap">
        @include('hr.payroll.partials.payslip-document', ['payslip' => $payslip])
    </div>
</div>

@if($payslip->payroll->status === 'pending')
<div class="no-print max-w-[210mm] mx-auto mt-6 flex justify-end">
    <form action="{{ route('hr.payroll.update', $payslip->payroll) }}" method="POST" class="flex gap-3">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="paid">
        <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white font-semibold rounded-lg text-sm hover:bg-emerald-700 transition-colors">
            Mark as Paid
        </button>
    </form>
</div>
@endif
@endsection
