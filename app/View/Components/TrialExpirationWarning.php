<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TrialExpirationWarning extends Component
{
    public $daysLeft;
    public $endsAt;

    public function __construct()
    {
        $subscription = auth()->user()?->subscription('default');

        if ($subscription?->onGracePeriod()) {
            $this->endsAt = Carbon::parse($subscription->ends_at);
            $this->daysLeft = intval(now()->diffInDays($this->endsAt, false));
        } else {
            $this->daysLeft = null;
            $this->endsAt = null;
        }
    }

    public function render()
    {
        return view('components.trial-expiration-warning');
    }
}
