<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use Illuminate\Http\Request;

use App\Models\Admin\AuditLog;

class AuditLogController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $logs = $this->paginateListing(AuditLog::with('user')->latest(), $request);
        return view('admin.audit-logs.index', compact('logs'));
    }
}
