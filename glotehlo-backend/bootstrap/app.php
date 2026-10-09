<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Validation\ValidationException;

use Throwable;

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
         $middleware->statefulApi(); //an API that is capable of maintaining a user session across multiple requests using traditional web mechanics. tsiupp

         $middleware->trustProxies(at: '*');
         $middleware->validateCsrfTokens(
    except: [
        'api/login',
        'api/logout',
        'api/departments/{departmentId}/interns',
        'api/sites',
        'api/departments',
        'api/sites/*/departments',
        'api/sites/*/networks',
        'api/sites/*/allnetworks',
        'api/clock-in',
        'api/clock-out',

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





        $exceptions->render(function (Throwable $e) {
    return response()->json([
        'error' => [
            'code' => 'SERVER_ERROR',
            'message' => 'Something went wrong',
        ],
    ], 500);
   });





    })->create();




