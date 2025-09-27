<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with('user', 'auditable');

        if ($request->filled('event')) {
            $search = $request->event;

            $query->where(function ($q) use ($search) {
                $q->where('event', 'like', "%{$search}%")
                  ->orWhere('user_type', 'like', "%{$search}%")
                  ->orWhere('auditable_type', 'like', "%{$search}%");
            })
            ->orWhereHasMorph(
                'user',
                ['App\Models\User'],
                function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                }
            );
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $audits = $query->orderBy('created_at', 'desc')->paginate(20);

        Log::info('Visualização de auditorias.', [
            'user_id' => Auth::id(),
            'filters' => $request->only(['event', 'date_from', 'date_to']),
        ]);

        return view('audits.index', compact('audits'));
    }
}
