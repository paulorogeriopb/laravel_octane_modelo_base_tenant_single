<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Capturar possíveis exceções durante a execução.
        try {
            //Modelo

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

            //Salva log
            Log::notice('Dashboard', ['action_user_id' => Auth::id()]);

            return view('dashboard.index', [
                'menu' => 'dashboard',
                'labels' => $labels,
                'data' => $data,
            ]);
        } catch (Exception $exception) {
            // Salvar log
            Log::notice('Dados para o gráfico não carregados.', ['error' => $exception->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Dados para o gráfico não carregados!');
        }
    }
}
