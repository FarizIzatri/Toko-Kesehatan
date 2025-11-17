<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\OrderCompleted;
use App\Listeners\SendInvoiceEmail;

class AppServiceProvider extends ServiceProvider
{

    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        // <-- TAMBAHKAN BLOK INI -->
        OrderCompleted::class => [
            SendInvoiceEmail::class,
        ],
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
