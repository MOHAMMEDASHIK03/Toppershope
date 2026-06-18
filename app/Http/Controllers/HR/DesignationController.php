<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $designations = $this->paginateListing(
            \App\Models\HR\Designation::with('department')->withCount('employees')->latest(),
            $request
        );
        return view('hr.designations.index', compact('designations'));
    }

    public function create()
    {
        $departments = \App\Models\HR\Department::where('is_active', true)->orderBy('name')->get();
        return view('hr.designations.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $data['is_active'] = $request->boolean('is_active', true);
        \App\Models\HR\Designation::create($data);

        return redirect()->route('hr.designations.index')->with('success', 'Designation created successfully.');
    }

    public function edit(\App\Models\HR\Designation $designation)
    {
        $departments = \App\Models\HR\Department::where('is_active', true)->orderBy('name')->get();
        return view('hr.designations.edit', compact('designation', 'departments'));
    }

    public function update(Request $request, \App\Models\HR\Designation $designation)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $data['is_active'] = $request->boolean('is_active', false);
        $designation->update($data);

        return redirect()->route('hr.designations.index')->with('success', 'Designation updated successfully.');
    }

    public function destroy(\App\Models\HR\Designation $designation)
    {
        if ($designation->employees()->count() > 0) {
            return back()->with('error', 'Cannot delete designation because it is assigned to employees.');
        }
        
        $designation->delete();
        return redirect()->route('hr.designations.index')->with('success', 'Designation deleted successfully.');
    }
}
