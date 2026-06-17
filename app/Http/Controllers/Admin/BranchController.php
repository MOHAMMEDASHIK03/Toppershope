<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Traits\LogsAdminActivity;

class BranchController extends Controller
{
    use LogsAdminActivity, PaginatesListings;

    public function index(Request $request)
    {
        $branches = $this->paginateListing(Branch::withCount('employees')->latest(), $request);
        return view('admin.branches.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:branches,code|max:50',
            'location' => 'nullable|string|max:255',
        ]);

        $branch = Branch::create($request->all());

        $this->logAudit('created_branch', "Established new physical/virtual branch: {$branch->name} ({$branch->code})");

        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully.');
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code,' . $branch->id,
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $branch->update([
            'name' => $request->name,
            'code' => $request->code,
            'location' => $request->location,
            'is_active' => request()->has('is_active')
        ]);

        $this->logAudit('updated_branch', "Updated details for branch: {$branch->name}");

        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $name = $branch->name;
        $branch->delete();
        
        $this->logAudit('deleted_branch', "Permanently deleted ecosystem branch: {$name}");

        return redirect()->route('admin.branches.index')->with('success', 'Branch deleted successfully.');
    }
}
