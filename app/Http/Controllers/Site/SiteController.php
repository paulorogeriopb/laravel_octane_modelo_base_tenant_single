<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Plan;

class SiteController extends Controller
{
    public function index(Plan $plan)
    {
        // Carrega todos os planos ativos
        $plans = Plan::where('active', true)->get();

        // Decodifica JSON de limits e features para cada plano
        $plans->transform(function ($plan) {
            $plan->limits = $plan->limits ? json_decode($plan->limits, true) : [];
            $plan->features = $plan->features ? json_decode($plan->features, true) : [];
            return $plan;
        });

        return view('site.index', compact('plans'));
    }



    public function createSessionPlan($slug)
{
    // Busca o plano pelo slug/url
    $plan = Plan::where('slug', $slug)->where('active', true)->first();

    if (!$plan) {
        return redirect()->route('site.index')
            ->with('error', __('Escolha um plano.'));
    }

    // Armazena o plano na sessão
    session()->put('plan', $plan);

    // Redireciona para a página de checkout
    return redirect()->route('subscriptions.checkout');
}
}
