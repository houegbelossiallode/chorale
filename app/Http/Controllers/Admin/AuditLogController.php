<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->latest()->paginate(20);
        $filteredUser = $request->has('user_id') ? \App\Models\User::find($request->user_id) : null;

        return view('admin.suivi.audit_logs', compact('logs', 'filteredUser'));
    }
}
