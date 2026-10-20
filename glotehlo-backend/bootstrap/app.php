<?php

use Illuminate\Foundation\Application;

use Illuminate\Foundation\Configuration\Exceptions;

use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Validation\ValidationException;

use App\Exceptions\AppException;

use Illuminate\Auth\AuthenticationException;

use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )


    //veri goude

   ->withMiddleware(function (Middleware $middleware): void {
    $middleware->redirectGuestsTo(fn () => null);
    $middleware->statefulApi();
    $middleware->trustProxies(at: '*');
    $middleware->validateCsrfTokens(
        except: [
            'api/login',
            'api/logout',
            'api/departments/*/interns',
            'api/sites',
            'api/departments',
            'api/sites/*/departments',
            'api/sites/*/addnetworks',
            'api/sites/*/allnetworks',
            'api/clock-in',
            'api/clock-out',
            'api/applications',
            'api/applications/*/approve',
            'api/delete',
            'api/applications/*/',
            'api/users/*',
            'api/applications/departments/*',
            'api/applications/*/reject',
            'api/users',
            'api/users/*/reset-password',
            'api/interns',
            'api/interns/*',
            'api/auth/password',
            'api/qr/*',  //get departments by slug
            'api/attendance/clock',
            'api/attendance/me/summary',
            'api/attendance/department/today',
            'api/attendance/department', //get department history
            'api/attendance/days/*/confirm'
        ]
    );
})




    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (ValidationException $e) {
       return response()->json([
        'error' => [
            'code' => 'VALIDATION_ERROR',
            'message' => 'The given data was invalid.',
            'fields' => $e->errors(),//it retrieves the individual validation errors including their fields
        ],
    ], 422);    //422 yeah ..   unfuckingprocessable
});


      $exceptions->render(function (AuthenticationException $e) {
        return response()->json([
          'error' => [
            'code' => 'UNAUTHENTICATED',
            'message' => 'Unauthenticated.',
        ],
    ], 401);
});


       $exceptions->render(function (AppException $e) {
           return response()->json([
        'error' => [
            'code' => $e->errorCode,
            'message' => $e->getMessage(),
           ],
       ], $e->httpStatus);
     });


       $exceptions->dontReport(AppException::class);



     /* $exceptions->render(function (\Throwable $e) {
    return response()->json([
        'error' => [
            'code' => 'SERVER_ERROR',
            'message' => 'Something went wrong',
        ],
    ], 500);
   }); */





    })->create();




