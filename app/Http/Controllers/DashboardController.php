<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Período de 12 meses
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $userByMonth = User::selectRaw("DATE_FORMAT(created_at, '%y-%m') as month, count(*) as total")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month'); // exemplo: ['25-08' => 3, '25-09' => 5]

        $labels = [];
        $data = [];

        for ($i = 0; $i < 12; $i++) {
            $month = $startDate->copy()->addMonths($i);
            $key = $month->format('y-m'); // <-- igual ao do SQL
            $labels[] = ucfirst($month->translatedFormat('F'));
            $data[] = $userByMonth->get($key, 0);
        }

        return view('dashboard.index', [
            'menu' => 'dashboard',
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}