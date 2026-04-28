<?php

namespace App\Providers;

use App\Network\Network;
use App\Rooms\RoomCodeTransliterator;
use App\Support\Config\Configuration;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Rooms\RoomCodeGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RoomCodeGenerator::class, function () {
            return new RoomCodeGenerator(
                Configuration::alphabet('rooms.code.alphabet.cyrillic'),
                Configuration::integer('rooms.code.length'),
            );
        });

        $this->app->singleton(Network::class, function () {
            return new Network();
        });

        $this->app->singleton(RoomCodeTransliterator::class, function () {
            return new RoomCodeTransliterator(
                Configuration::alphabet('rooms.code.alphabet.cyrillic'),
                Configuration::alphabet('rooms.code.alphabet.latin'),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        JsonResource::withoutWrapping();
        Model::preventLazyLoading();
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn(): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null);
    }
}
