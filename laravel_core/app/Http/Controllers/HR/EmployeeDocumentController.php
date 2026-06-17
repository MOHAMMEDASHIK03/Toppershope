<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use App\Models\HR\Employee;
use App\Models\HR\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeDocumentController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $query = EmployeeDocument::with('employee')->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();
            $query->where(function ($q) use ($search) {
                $q->where('document_name', 'like', "%{$search}%")
                    ->orWhere('document_type', 'like', "%{$search}%")
                    ->orWhere('file_path', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                        $employeeQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('employee_id', 'like', "%{$search}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                    });
            });
        }

        $documents = $this->paginateListing($query, $request);

        return view('hr.employee_documents.index', compact('documents'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        return view('hr.employee_documents.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'document_name' => 'required|string|max:255',
            'document_type' => 'required|string|in:'.implode(',', array_keys(EmployeeDocument::categoryLabels())),
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        $filePath = $request->file('file')->store('employee_documents', 'public');

        EmployeeDocument::create([
            'employee_id' => $request->employee_id,
            'document_name' => $request->document_name,
            'document_type' => $request->document_type,
            'file_path' => $filePath,
        ]);

        return redirect()
            ->route('hr.employee-documents.index', $request->only('search'))
            ->with('success', 'Document uploaded successfully.');
    }

    public function destroy(EmployeeDocument $employeeDocument)
    {
        if ($employeeDocument->file_path) {
            Storage::disk('public')->delete($employeeDocument->file_path);
        }

        $employeeDocument->delete();
        return redirect()
            ->route('hr.employee-documents.index', request()->only('search'))
            ->with('success', 'Document deleted.');
    }
}
