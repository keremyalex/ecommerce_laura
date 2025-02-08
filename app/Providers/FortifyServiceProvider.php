<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::loginView(function () {
            return view('auth.login'); // Vista de login
        });

        Fortify::registerView(function () {
            return view('auth.register'); // Vista de registro
        });
    
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password'); // Vista de recuperación
        });
    
        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]); // Vista de restablecimiento
        });

        // RateLimiter::for('login', function (Request $request) {
        //     $email = (string) $request->email;

        //     return Limit::perMinute(5)->by($email.$request->ip());
        // });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

             // Configura el límite de intentos de inicio de sesión
        $limiter = Limit::perMinute(3)->by($email.$request->ip());
            
        if (RateLimiter::tooManyAttempts($email.$request->ip(), 3)) {
            session()->flash('throttle', 'Demasiados intentos fallidos. Inténtalo de nuevo en 1 minuto.');
            throw ValidationException::withMessages([
                    'email' => ['Demasiados intentos fallidos. Inténtalo de nuevo en 1 minuto.']
                ]);
            }

            return $limiter;
            
        
            // return Limit::perMinutes(1, 5)->by($email.$request->ip()); // Bloquea por 1 minuto tras 5 intentos fallidos
        });
        

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
