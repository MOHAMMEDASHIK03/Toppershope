<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $departments = $this->paginateListing(
            \App\Models\HR\Department::withCount('employees')->latest(),
            $request
        );
        return view('hr.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('hr.departments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $data['is_active'] = $request->boolean('is_active', true);
        \App\Models\HR\Department::create($data);

        return redirect()->route('hr.departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(\App\Models\HR\Department $department)
    {
        return view('hr.departments.edit', compact('department'));
    }

    public function update(Request $request, \App\Models\HR\Department $department)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $data['is_active'] = $request->boolean('is_active', false);
        $department->update($data);

        return redirect()->route('hr.departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(\App\Models\HR\Department $department)
    {
        if ($department->employees()->count() > 0) {
            return back()->with('error', 'Cannot delete department because it has active employees.');
        }
        
        $department->delete();
        return redirect()->route('hr.departments.index')->with('success', 'Department deleted successfully.');
    }
}
