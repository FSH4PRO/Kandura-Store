<?php


use App\Http\Middleware\CheckAuthenticated;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\TestCookie;
use App\Http\Middleware\TrustProxies;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Middleware\PermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )

  ->withSchedule(function (Schedule $schedule) {
    $schedule->command('orders:cancel-expired')->dailyAt('01:00');
  })

  ->withMiddleware(function (Middleware $middleware) {

    $middleware->trustProxies(
      at: '*',
    );

    $middleware->alias([
      'check.authenticated' => CheckAuthenticated::class,
      'permission' => PermissionMiddleware::class,
      'test.cookie' => TestCookie::class
    ]);

    $middleware->web(append: [
      SetLocale::class,
    ]);

    $middleware->append(TrustProxies::class);
  })

  ->withExceptions(function (Exceptions $exceptions) {
    // Guarantee JSON error responses for every /api/* request regardless
    // of the client's Accept header. Previously this relied entirely on
    // the caller sending "Accept: application/json" — a client that
    // omitted it (a bare curl request, a misconfigured HTTP client, a
    // webhook retry tool, etc.) could be served an HTML error page
    // instead of the JSON the API doc promises.
    $exceptions->shouldRenderJsonWhen(function ($request, \Throwable $e) {
      return $request->is('api/*') || $request->expectsJson();
    });

    // Laravel's default ModelNotFoundException message leaks the
    // internal Eloquent class name and id (e.g. "No query results for
    // model [App\Models\Design] 42"). Replace it with a clean, generic
    // 404 for API requests — the shape (single `message` key) is
    // unchanged, so existing clients don't need to change anything.
    $exceptions->render(function (ModelNotFoundException $e, $request) {
      if ($request->is('api/*') || $request->expectsJson()) {
        return response()->json([
          'message' => 'The requested resource was not found.',
        ], 404);
      }
    });

    // ValidationException, AuthenticationException, and
    // AuthorizationException are intentionally left on Laravel's own
    // default JSON rendering here (422 { message, errors }, 401
    // { message }, 403 { message } respectively) — that is the exact
    // shape the customer API documentation already specifies and the
    // frontend already relies on. shouldRenderJsonWhen() above is what
    // ensures they always come back as JSON instead of HTML.
  })->create();
