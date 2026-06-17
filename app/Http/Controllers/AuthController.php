<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserSession;
use App\Models\User;
use App\Rules\SubcategoryBelongsToCategory;
use App\Services\CategoryService;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm(CategoryService $categoryService)
    {
        $categories = $categoryService->activeCategoriesWithSubcategories();

        return view('auth.register', compact('categories'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:15'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'dob' => ['required', 'date'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['required', 'exists:subcategories,id', new SubcategoryBelongsToCategory($request->integer('category_id'))],
            'district' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
        ]);

        $category = \App\Models\Category::find($validated['category_id']);
        $subcategory = \App\Models\Subcategory::find($validated['subcategory_id']);

        $legacyExam = $category?->landing('legacy_course_category') ?? $category?->name;
        $gradeMap = [
            'class-11' => '11th',
            'class-12' => '12th',
            'dropper' => '12+',
        ];
        $gradeCategory = $gradeMap[$subcategory?->slug] ?? $subcategory?->name ?? '11th';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'dob' => $validated['dob'],
            'category_id' => $validated['category_id'],
            'subcategory_id' => $validated['subcategory_id'],
            'target_exam' => in_array($legacyExam, ['JEE', 'NEET'], true) ? $legacyExam : ($category?->name ?? 'JEE'),
            'grade_category' => $gradeCategory,
            'district' => $validated['district'],
            'state' => $validated['state'],
        ]);

        // Assign default Student role
        $studentRole = \Illuminate\Support\Facades\DB::table('roles')->where('slug', 'student')->first();
        if ($studentRole) {
            \Illuminate\Support\Facades\DB::table('user_role')->insert([
                'user_id' => $user->id,
                'role_id' => $studentRole->id
            ]);
        }

        Auth::login($user);

        // Single Session Logic
        $request->session()->regenerate();
        UserSession::create([
            'user_id' => $user->id,
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'last_activity' => now(),
        ]);

        // Redirect students to the student panel
        return redirect()->route('student.dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Single Session Enforcement Logic
            // 1. Delete previous session record
            UserSession::where('user_id', $user->id)->delete();

            // 2. Regenerate session ID
            $request->session()->regenerate();

            // 3. Store new session
            UserSession::create([
                'user_id' => $user->id,
                'session_id' => session()->getId(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'last_activity' => now(),
            ]);

            return redirect()->route('student.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            UserSession::where('user_id', Auth::id())->delete();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
