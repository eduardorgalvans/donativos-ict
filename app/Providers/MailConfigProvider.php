<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MailConfigProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // como se configura https://programmingfields.com/dynamic-email-configuration-in-laravel-8-for-sending-email/
        // get email view data in provider class
        /*
        view()->composer('email', function ($view) {
            if(isset(Auth::user()->id)) {
                $configuration = EmailConfiguration::where("user_id", Auth::user()->id)->first();
                if(!is_null($configuration)) {
                    $config = array(
                        'driver'     =>     $configuration->driver,
                        'host'       =>     $configuration->host,
                        'port'       =>     $configuration->port,
                        'username'   =>     $configuration->user_name,
                        'password'   =>     $configuration->password,
                        'encryption' =>     $configuration->encryption,
                        'from'       =>     array('address' => $configuration->sender_email, 'name' => $configuration->sender_name),
                    );
                    Config::set('mail', $config);
                }
            }
        });
        */
        //
    }
}
