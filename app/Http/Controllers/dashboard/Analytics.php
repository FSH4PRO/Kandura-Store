<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Customer;

class Analytics extends Controller
{
  public function index()
  {
    
    $totalUsers = User::query()
      ->whereHasMorph('usable', [Customer::class])
      ->count();

    
    $totalActiveUsers = User::query()
      ->where('is_active', true)
      ->whereHasMorph('usable', [Customer::class])
      ->count();

    
    $totalAdmins = User::query()
      ->whereHasMorph('usable', [Admin::class])
      ->count();

    
    $totalAddresses = Address::count();

    
    

    return view('content.dashboard.dashboards-analytics', compact(
      'totalUsers',
      'totalActiveUsers',
      'totalAdmins',
      'totalAddresses',
     
    ));
  }
}
