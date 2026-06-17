<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Traits\LogsAdminActivity;

class AccessControlController extends Controller
{
    use LogsAdminActivity;

    /**
     * Show the Force Password Reset page with all users across panels.
     */
    public function passwordResetIndex()
    {
        // Gather all users from all panels
        $hrUsers = \App\Models\HR\HrUser::select('id', 'name', 'email', 'is_active')
            ->get()->map(fn($u) => (object)[
                'id' => $u->id, 'name' => $u->name, 'email' => $u->email,
                'role' => 'hr_staff', 'panel' => 'HR', 'guard' => 'hr',
                'is_active' => $u->is_active, 'model' => 'HrUser',
            ]);

        $adsUsers = \App\Models\Ads\AdsUser::select('id', 'name', 'email')
            ->get()->map(fn($u) => (object)[
                'id' => $u->id, 'name' => $u->name, 'email' => $u->email,
                'role' => 'ads_user', 'panel' => 'Ads', 'guard' => 'ads',
                'is_active' => true, 'model' => 'AdsUser',
            ]);

        $admissionUsers = \App\Models\Admission\AdmissionUser::select('id', 'name', 'email')
            ->get()->map(fn($u) => (object)[
                'id' => $u->id, 'name' => $u->name, 'email' => $u->email,
                'role' => 'admission_user', 'panel' => 'Admission', 'guard' => 'admission',
                'is_active' => true, 'model' => 'AdmissionUser',
            ]);

        $facultyUsers = \App\Models\User::whereIn('role', ['faculty', 'faculty_head'])
            ->select('id', 'name', 'email', 'role')
            ->get()->map(fn($u) => (object)[
                'id' => $u->id, 'name' => $u->name, 'email' => $u->email,
                'role' => $u->role, 'panel' => 'Faculty', 'guard' => 'web',
                'is_active' => true, 'model' => 'User',
            ]);

        $allUsers = collect()->merge($hrUsers)->merge($adsUsers)->merge($admissionUsers)->merge($facultyUsers);

        return view('admin.access-control.password-reset', compact('allUsers'));
    }

    /**
     * Force reset a user's password.
     */
    public function forcePasswordReset(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'user_id' => 'required|integer',
            'new_password' => 'required|string|min:6',
        ]);

        $modelMap = [
            'HrUser' => \App\Models\HR\HrUser::class,
            'AdsUser' => \App\Models\Ads\AdsUser::class,
            'AdmissionUser' => \App\Models\Admission\AdmissionUser::class,
            'User' => \App\Models\User::class,
        ];

        $modelClass = $modelMap[$request->model] ?? null;
        if (!$modelClass) {
            return back()->with('error', 'Invalid user model specified.');
        }

        $user = $modelClass::find($request->user_id);
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        // Kill all sessions for this user
        if (in_array($request->model, ['User'])) {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        $this->logAudit('force_password_reset', "Force reset password for {$user->name} ({$request->model} ID:{$user->id})");

        return back()->with('success', "Password for {$user->name} has been reset successfully.");
    }

    /**
     * Show active sessions overview.
     */
    public function sessionsIndex(Request $request)
    {
        $search = $request->query('search');

        $sessions = DB::table('sessions')
            ->whereNotNull('user_id')
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) {
                // Try to find user from multiple tables
                $user = null;
                $panel = 'Unknown';

                $user = \App\Models\User::find($session->user_id);
                if ($user) {
                    $panel = in_array($user->role, ['faculty', 'faculty_head']) ? 'Faculty' : 'Student';
                }

                if (!$user) {
                    $user = \App\Models\HR\HrUser::find($session->user_id);
                    if ($user) $panel = 'HR';
                }

                if (!$user) {
                    $user = \App\Models\Admin\Admin::find($session->user_id);
                    if ($user) $panel = 'Admin';
                }

                return (object)[
                    'id' => $session->id,
                    'user_id' => $session->user_id,
                    'user_name' => $user->name ?? 'Unknown User',
                    'user_email' => $user->email ?? '—',
                    'panel' => $panel,
                    'ip_address' => $session->ip_address,
                    'user_agent' => Str::limit($session->user_agent, 80),
                    'last_activity' => \Carbon\Carbon::createFromTimestamp($session->last_activity),
                ];
            });

        // Apply search filter
        if ($search) {
            $search = strtolower($search);
            $sessions = $sessions->filter(function ($s) use ($search) {
                return str_contains(strtolower($s->user_name), $search)
                    || str_contains(strtolower($s->user_email), $search)
                    || str_contains((string)$s->user_id, $search)
                    || str_contains(strtolower($s->ip_address), $search);
            });
        }

        return view('admin.access-control.sessions', compact('sessions', 'search'));
    }

    /**
     * Kill a specific session.
     */
    public function killSession(Request $request)
    {
        $request->validate(['session_id' => 'required|string']);

        $sessionId = $request->input('session_id');
        $session = DB::table('sessions')->where('id', $sessionId)->first();

        if (!$session) {
            return back()->with('error', 'Session not found or already expired.');
        }

        DB::table('sessions')->where('id', $sessionId)->delete();

        $this->logAudit('session_killed', "Killed session for user ID {$session->user_id} (IP: {$session->ip_address})");

        return back()->with('success', 'Session terminated successfully.');
    }
}
