<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\Admin\DesignOptionController;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DesignController as AdminDesignController;



// layout
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');




// cards
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

// User Interface
Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// icons
Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

// form elements
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');













// ========================
// Dashboard Home
// ========================
Route::get('/', [Analytics::class, 'index'])
  ->middleware(['check.authenticated', 'permission:dashboard.access'])
  ->name('dashboard-analytics');


// ========================
// Admin Auth Routes
// ========================
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
  ->middleware('guest:admin')
  ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
  ->middleware('guest:admin')
  ->name('admin.login.post');

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
  ->middleware('auth:admin')
  ->name('admin.logout');


// ========================
// Admin Panel (protected)
// ========================
Route::prefix('admin')
  ->middleware(['check.authenticated'])
  ->group(function () {

    // 1) صفحة المستخدمين (Customers) → role: manage_users أو super_admin
    Route::get('/users', [UserController::class, 'index'])
      ->middleware('permission:users.view')
      ->name('users.index');

    // 2) صفحة الأدمنز → role: manage_admins أو super_admin
    Route::get('/admins', [UserController::class, 'adminsIndex'])
      ->middleware('permission:admins.view')
      ->name('admins.index');

    // 3) إنشاء أدمن جديد → نفس الشي: manage_admins أو super_admin
    Route::get('/admins/create', [UserController::class, 'createAdmin'])
      ->middleware('permission:admins.create')
      ->name('admins.create');

    Route::post('/admins', [UserController::class, 'storeAdmin'])
      ->middleware('permission:admins.create')
      ->name('admins.store');

    Route::get('/admins/{user}/edit', [UserController::class, 'editAdmin'])->middleware('permission:admins.edit')->name('admins.edit');


    Route::put('/admins/{user}', [UserController::class, 'updateAdmin'])->middleware('permission:admins.edit')->name('admins.update');


    Route::delete('/admins/{user}', [UserController::class, 'destroy'])
      ->middleware('permission:admins.delete')
      ->name('admins.destroy');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
      ->middleware('permission:users.delete')
      ->name('users.destroy');
  });

Route::middleware(['check.authenticated', 'permission:roles.view'])
  ->prefix('admin')
  ->group(function () {
    Route::resource('roles', RoleController::class)
      ->except(['show']);
  })->name('roles.index');






Route::prefix('admin')
  ->middleware(['check.authenticated'])
  ->group(function () {
    Route::middleware('permission:design_options.view')->group(function () {
      Route::get('/design-options', [DesignOptionController::class, 'index'])
        ->name('admin.design-options.index');

      Route::get('/design-options/create', [DesignOptionController::class, 'create'])
        ->middleware('permission:design_options.create')->name('admin.design-options.create');

      Route::post('/design-options', [DesignOptionController::class, 'store'])
        ->middleware('permission:design_options.create')->name('admin.design-options.store');

      Route::get('/design-options/{designOption}/edit', [DesignOptionController::class, 'edit'])
        ->middleware('permission:design_options.edit')->name('admin.design-options.edit');

      Route::put('/design-options/{designOption}', [DesignOptionController::class, 'update'])
        ->middleware('permission:design_options.edit')->name('admin.design-options.update');

      Route::delete('/design-options/{designOption}', [DesignOptionController::class, 'destroy'])
        ->middleware('permission:design_options.delete')->name('admin.design-options.destroy');
    });

    // Admin designs listing
    Route::middleware('permission:designs.view')->group(function () {
      Route::get('/designs', [AdminDesignController::class, 'index'])
        ->name('admin.designs.index');

      Route::get('/designs/{design}', [AdminDesignController::class, 'show'])
        ->name('admin.designs.show');
    });
  });

//orders 
Route::prefix('admin')
  ->middleware(['check.authenticated', 'permission:orders.view'])
  ->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
  });





// ========================
// Locale Switch 
// ========================



Route::get("change_lang", function () {
  $lang = App::getLocale();
  if ($lang == "ar") {
    App::setLocale("en");
    session(['locale' => 'en']);
  } else {
    App::setLocale("ar");
    session(['locale' => 'ar']);
  }
  return redirect()->back();
})->name('switch.lang');
