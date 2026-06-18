<?php

namespace App\Traits;

use App\Models\Admin\AuditLog;
use Illuminate\Support\Facades\Request;

trait LogsAdminActivity
{
    /**
     * Log a master ecosystem action to the Admin Audit database.
     *
     * @param string $action The action identifier (e.g. "created_employee", "deleted_course")
     * @param string|null $description A human-readable detailed explanation
     * @param mixed|null $user The eloquent user model performing the action. If null, uses currently authenticated user across any guard.
     */
    protected function logAudit(string $action, ?string $description = null, $user = null): void
    {
        if (!$user) {
            // Attempt to resolve the user traversing the active guard
            $user = auth('admin')->user() 
                    ?? auth('hr')->user() 
                    ?? auth('ads')->user() 
                    ?? auth('admission')->user() 
                    ?? auth('web')->user();
        }

        AuditLog::create([
            'user_type' => $user ? get_class($user) : null,
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent()
        ]);
    }
}
