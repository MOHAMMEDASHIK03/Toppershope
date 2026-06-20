<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use Illuminate\Http\Request;

class HrUserController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $hrUsers = $this->paginateListing(\App\Models\HR\HrUser::latest(), $request);
        return view('admin.hr_users.index', compact('hrUsers'));
    }

    public function create()
    {
        return view('admin.hr_users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:hr_users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'boolean'
        ]);

        \App\Models\HR\HrUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'hr_manager',
            'is_active' => $request->has('is_active') ? $request->is_active : true,
        ]);

        return redirect()->route('admin.hr-users.index')->with('success', 'HR User created successfully.');
    }

    public function edit(\App\Models\HR\HrUser $hrUser)
    {
        return view('admin.hr_users.edit', compact('hrUser'));
    }

    public function update(Request $request, \App\Models\HR\HrUser $hrUser)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:hr_users,email,' . $hrUser->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'boolean'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->has('is_active') ? $request->is_active : false,
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $hrUser->update($data);

        return redirect()->route('admin.hr-users.index')->with('success', 'HR User updated successfully.');
    }

    public function destroy(\App\Models\HR\HrUser $hrUser)
    {
        $hrUser->delete();
        return redirect()->route('admin.hr-users.index')->with('success', 'HR User deleted.');
    }
}
