<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use Illuminate\Http\Request;
use App\Models\HR\Kpi;

class KpiController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $kpis = $this->paginateListing(
            Kpi::with(['department', 'designation'])->latest(),
            $request
        );
        return view('hr.kpis.index', compact('kpis'));
    }

    public function create()
    {
        return view('hr.kpis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
        ]);

        Kpi::create($this->kpiAttributes($request));

        return redirect()->route('hr.kpis.index')->with('success', 'KPI framework created successfully.');
    }

    public function edit(Kpi $kpi)
    {
        return view('hr.kpis.edit', compact('kpi'));
    }

    public function update(Request $request, Kpi $kpi)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
        ]);

        $kpi->update($this->kpiAttributes($request));

        return redirect()->route('hr.kpis.index')->with('success', 'KPI framework updated successfully.');
    }

    public function destroy(Kpi $kpi)
    {
        $kpi->delete();
        return redirect()->route('hr.kpis.index')->with('success', 'KPI framework deleted successfully.');
    }

    private function kpiAttributes(Request $request): array
    {
        return [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'department_id' => $request->filled('department_id') ? $request->input('department_id') : null,
            'designation_id' => $request->filled('designation_id') ? $request->input('designation_id') : null,
        ];
    }
}
