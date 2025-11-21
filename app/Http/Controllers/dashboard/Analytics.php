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
    // 👤 عدد الـ customers (users العاديين)
    $totalUsers = User::query()
      ->whereHasMorph('usable', [Customer::class])
      ->count();

    // 👤 المستخدمين الفعالين من الـ customers فقط
    $totalActiveUsers = User::query()
      ->where('is_active', true)
      ->whereHasMorph('usable', [Customer::class])
      ->count();

    // 🧑‍💼 عدد الإدمنز (Admin + Super Admin) = كل User مربوط بـ Admin
    $totalAdmins = User::query()
      ->whereHasMorph('usable', [Admin::class])
      ->count();

    // 📍 عدد العناوين
    $totalAddresses = Address::count();

    // آخر 5 مستخدمين (أيًا كان نوعهم) مع الـ usable (Admin/Customer)
    $latestUsers = User::with('usable')
      ->latest()
      ->take(5)
      ->get();

    return view('content.dashboard.dashboards-analytics', compact(
      'totalUsers',
      'totalActiveUsers',
      'totalAdmins',
      'totalAddresses',
      'latestUsers'
    ));
  }
}
