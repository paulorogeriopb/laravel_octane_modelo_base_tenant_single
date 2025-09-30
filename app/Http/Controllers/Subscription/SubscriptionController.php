<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function checkout()
    {
        $subscription = auth()->user()->subscription('default');

        if ($subscription) {
            // Se assinatura está ativa OU ainda no período de graça
            if ($subscription->valid() || $subscription->onGracePeriod()) {
                return redirect()->route('subscriptions.start');
            }
        }

        // Se não tem assinatura ou já expirou -> abre checkout
        return view('subscriptions.checkout', [
            'intent' => auth()->user()->createSetupIntent(),
        ]);
    }

    public function store(Request $request)
    {
        $request->user()
            ->newSubscription('default', 'price_1SCDhjE23YTKTG0iz2bn5U5x')
            ->create($request->token);

        return redirect()->route('subscriptions.start');
    }

    public function start(Request $request)
    {


    return view('subscriptions.start');
    }
}
