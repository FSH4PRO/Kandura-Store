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
    use App\Http\Controllers\Admin\CouponController;
    use App\Http\Controllers\Admin\SearchController;
    use App\Http\Controllers\Admin\WalletController;
    use App\Http\Controllers\dashboard\Transactions;
    use App\Http\Controllers\user_interface\Buttons;
    use App\Http\Controllers\Admin\InvoiceController;
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
    use App\Http\Controllers\Webhooks\WebhookController;
    use App\Http\Controllers\form_layouts\HorizontalForm;
    use App\Http\Controllers\tables\Basic as TablesBasic;
    use App\Http\Controllers\Admin\DesignOptionController;
    use App\Http\Controllers\Admin\NotificationController;
    use App\Http\Controllers\extended_ui\PerfectScrollbar;
    use App\Http\Controllers\pages\AccountSettingsAccount;
    use App\Http\Controllers\Admin\AdminFcmTokenController;
    use App\Http\Controllers\authentications\RegisterBasic;
    use App\Http\Controllers\user_interface\TooltipsPopovers;
    use App\Http\Controllers\pages\AccountSettingsConnections;
    use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
    use App\Http\Controllers\pages\AccountSettingsNotifications;
    use App\Http\Controllers\authentications\ForgotPasswordBasic;
    use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
    use App\Http\Controllers\Admin\AuthController as AdminAuthController;
    use App\Http\Controllers\Admin\DesignController as AdminDesignController;

    // ========================
    // Layout Routes
    // ========================
    Route::prefix('layouts')->group(function () {
        Route::get('/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
        Route::get('/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
        Route::get('/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
        Route::get('/container', [Container::class, 'index'])->name('layouts-container');
        Route::get('/blank', [Blank::class, 'index'])->name('layouts-blank');
    });

    // ========================
    // Pages Routes
    // ========================
    Route::prefix('pages')->group(function () {
        Route::get('/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
        Route::get('/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
        Route::get('/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
        Route::get('/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
        Route::get('/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');
    });

    // ========================
    // Authentication Routes
    // ========================
    Route::prefix('auth')->group(function () {
        Route::get('/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
        Route::get('/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
        Route::get('/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');
    });

    // ========================
    // Cards Routes
    // ========================
    Route::prefix('cards')->group(function () {
        Route::get('/basic', [CardBasic::class, 'index'])->name('cards-basic');
    });

    // ========================
    // User Interface Routes
    // ========================
    Route::prefix('ui')->group(function () {
        Route::get('/accordion', [Accordion::class, 'index'])->name('ui-accordion');
        Route::get('/alerts', [Alerts::class, 'index'])->name('ui-alerts');
        Route::get('/badges', [Badges::class, 'index'])->name('ui-badges');
        Route::get('/buttons', [Buttons::class, 'index'])->name('ui-buttons');
        Route::get('/carousel', [Carousel::class, 'index'])->name('ui-carousel');
        Route::get('/collapse', [Collapse::class, 'index'])->name('ui-collapse');
        Route::get('/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
        Route::get('/footer', [Footer::class, 'index'])->name('ui-footer');
        Route::get('/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
        Route::get('/modals', [Modals::class, 'index'])->name('ui-modals');
        Route::get('/navbar', [Navbar::class, 'index'])->name('ui-navbar');
        Route::get('/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
        Route::get('/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
        Route::get('/progress', [Progress::class, 'index'])->name('ui-progress');
        Route::get('/spinners', [Spinners::class, 'index'])->name('ui-spinners');
        Route::get('/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
        Route::get('/toasts', [Toasts::class, 'index'])->name('ui-toasts');
        Route::get('/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
        Route::get('/typography', [Typography::class, 'index'])->name('ui-typography');
    });

    // ========================
    // Extended UI Routes
    // ========================
    Route::prefix('extended')->group(function () {
        Route::get('/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
        Route::get('/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');
    });

    // ========================
    // Icons Routes
    // ========================
    Route::prefix('icons')->group(function () {
        Route::get('/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');
    });

    // ========================
    // Form Elements Routes
    // ========================
    Route::prefix('forms')->group(function () {
        Route::get('/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
        Route::get('/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');
    });

    // ========================
    // Form Layouts Routes
    // ========================
    Route::prefix('form/layouts')->group(function () {
        Route::get('/vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
        Route::get('/horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');
    });

    // ========================
    // Tables Routes
    // ========================
    Route::prefix('tables')->group(function () {
        Route::get('/basic', [TablesBasic::class, 'index'])->name('tables-basic');
    });

    // ========================
    // Dashboard Home
    // ========================
    Route::get('/', [Analytics::class, 'index'])
        ->middleware(['check.authenticated', 'permission:dashboard.access'])
        ->name('dashboard-analytics');

    // ========================
    // Transactions
    // ========================
    Route::get('/transactions', [Transactions::class, 'index'])
        ->middleware(['check.authenticated', 'permission:transactions.view'])
        ->name('dashboard.transactions.index');

    Route::get('/transactions/{transaction}', [Transactions::class, 'show'])
        ->middleware(['check.authenticated', 'permission:transactions.view'])
        ->name('dashboard.transactions.show');

    // ========================
    // Admin Authentication Routes
    // ========================
    Route::prefix('admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])
            ->middleware('guest:admin')
            ->name('admin.login');

        Route::post('/login', [AdminAuthController::class, 'login'])
            ->middleware('guest:admin')
            ->name('admin.login.post');

        Route::post('/logout', [AdminAuthController::class, 'logout'])
            ->middleware('auth:admin')
            ->name('admin.logout');
    });

    // ========================
    // Admin Panel (protected)
    // ========================
    Route::prefix('admin')
        ->middleware(['check.authenticated'])
        ->group(function () {
            Route::middleware('permission:users.view')->group(function () {
                Route::get('/users', [UserController::class, 'index'])->name('users.index');
                Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
                Route::get('/users/{user}/addresses', [UserController::class, 'getUserAddresses'])->name('users.addresses');
            });

            Route::middleware('permission:admins.view')->group(function () {
                Route::get('/admins', [UserController::class, 'adminsIndex'])->name('admins.index');
                Route::get('/admins/create', [UserController::class, 'createAdmin'])
                    ->middleware('permission:admins.create')
                    ->name('admins.create');

                Route::post('/admins', [UserController::class, 'storeAdmin'])
                    ->middleware('permission:admins.create')
                    ->name('admins.store');

                Route::get('/admins/{user}/edit', [UserController::class, 'editAdmin'])
                    ->middleware('permission:admins.edit')
                    ->name('admins.edit');

                Route::put('/admins/{user}', [UserController::class, 'updateAdmin'])
                    ->middleware('permission:admins.edit')
                    ->name('admins.update');

                Route::delete('/admins/{user}', [UserController::class, 'destroy'])
                    ->middleware('permission:admins.delete')
                    ->name('admins.destroy');
            });
        });

    Route::middleware(['check.authenticated', 'permission:roles.view'])
        ->prefix('admin')
        ->group(function () {
            Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
            Route::get('/roles/create', [RoleController::class, 'create'])->middleware('permission:roles.create')->name('roles.create');
            Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:roles.create')->name('roles.store');
            Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:roles.edit')->name('roles.edit');
            Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:roles.edit')->name('roles.update');
            Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:roles.delete')->name('roles.destroy');
        });
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
            Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus')->middleware('permission:orders.edit');
        });

    //wallets
    Route::prefix('admin')
        ->middleware(['check.authenticated', 'permission:wallets.view'])
        ->group(function () {
            Route::get('/wallets', [WalletController::class, 'index'])->name('admin.wallets.index');
            Route::get('/wallets/{wallet}', [WalletController::class, 'show'])->name('admin.wallets.show');
            Route::post('/wallets/{wallet}/topup', [WalletController::class, 'topup'])->name('admin.wallets.topup');
            Route::post('/wallets/bulk-topup', [WalletController::class, 'bulkTopup'])->name('admin.wallets.bulk-topup');
            Route::patch('/wallets/{wallet}/activate', [WalletController::class, 'activate'])->name('admin.wallets.activate');
            Route::patch('/wallets/{wallet}/deactivate', [WalletController::class, 'deactivate'])->name('admin.wallets.deactivate');
        });

    //coupons
    Route::prefix('admin')
        ->middleware(['check.authenticated', 'permission:coupons.view'])
        ->group(function () {
            Route::get('/coupons', [CouponController::class, 'index'])->middleware('permission:coupons.view')->name('coupons.index');
            Route::get('/coupons/create', [CouponController::class, 'create'])->middleware('permission:coupons.create')->name('coupons.create')->middleware('permission:coupons.create');
            Route::post('/coupons', [CouponController::class, 'store'])->middleware('permission:coupons.create')->name('coupons.store');
            Route::get('/coupons/{coupon}', [CouponController::class, 'show'])->middleware('permission:coupons.view')->name('coupons.show');
            Route::get('/coupons/{coupon}/edit', [CouponController::class, 'edit'])->middleware('permission:coupons.edit')->name('coupons.edit')->middleware('permission:coupons.edit');
            Route::put('/coupons/{coupon}', [CouponController::class, 'update'])->middleware('permission:coupons.edit')->name('coupons.update');
            Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->middleware('permission:coupons.delete')->name('coupons.destroy')->middleware('permission:coupons.delete');
            Route::patch('/coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->middleware('permission:coupons.edit')->name('admin.coupons.toggle');
        });


    //invoices
    Route::prefix('admin')
        ->middleware(['check.authenticated', 'permission:invoices.view'])
        ->group(function () {
            Route::get('/invoices', [InvoiceController::class, 'index'])->name('admin.invoices.index');
            Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('admin.invoices.show');
        });

    Route::prefix('admin')
        ->middleware(['check.authenticated', 'permission:reviews.view'])
        ->group(function () {
            Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('admin.reviews.index');
            Route::get('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('admin.reviews.show');
        });



    //notifications
    Route::prefix('admin')
        ->middleware(['check.authenticated'])
        ->group(function () {
            Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index')->middleware('permission:notifications.view');
            Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('admin.notifications.show')->middleware('permission:notifications.view');
            Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('admin.notifications.mark-read');
            Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('admin.notifications.mark-all-read');
            Route::post('/fcm-token', [AdminFcmTokenController::class, 'store'])->name('admin.fcm-token.store');
            Route::post('/fcm-test', [AdminFcmTokenController::class, 'sendTest'])->name('admin.fcm-test');
        });

    //search
    Route::prefix('admin')
        ->middleware(['check.authenticated'])
        ->group(function () {
            Route::get('/search', [SearchController::class, 'index'])->name('admin.search.index');
            Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('admin.search.suggestions');
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



    Route::get('/test-session', function (Illuminate\Http\Request $request) {

        session(['test' => 'hello']);

        return response()->json([
            'session_id' => session()->getId(),
            'session_value' => session('test'),
            'url' => $request->url(),
            'secure' => $request->secure(),
            'scheme' => $request->getScheme(),
            'https_header' => $request->header('X-Forwarded-Proto'),
        ]);
    });
