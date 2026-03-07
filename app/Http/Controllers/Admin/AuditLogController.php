<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.suivi.audit_logs', compact('logs'));
    }
}
