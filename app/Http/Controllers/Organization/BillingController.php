<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function index()
    {
        $organization = Auth::user()->organization;
        $plans = [
            'starter' => ['name' => 'Starter', 'price' => 49, 'events' => 5, 'users' => 1],
            'professional' => ['name' => 'Professional', 'price' => 149, 'events' => 25, 'users' => 5],
            'enterprise' => ['name' => 'Enterprise', 'price' => 399, 'events' => 'Unbegrenzt', 'users' => 20],
        ];
        return view('organization.billing', compact('organization', 'plans'));
    }
}
