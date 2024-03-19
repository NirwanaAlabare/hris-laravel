<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class SmtpConfigProvider extends ServiceProvider
{


    public function register()
    {
        try {
            $setting = DB::table('settings')->first();

            if ($setting) {

                if (\config('app.env') !== 'development') {

                    Config::set('mail.driver', $setting->mail_driver);
                    Config::set('mail.host', $setting->mail_host);
                    Config::set('mail.port', $setting->mail_port);
                    Config::set('mail.username', $setting->mail_username);
                    Config::set('mail.password', $setting->mail_password);
                    Config::set('mail.encryption', $setting->mail_encryption);
                    Config::set('app.name', $setting->website);

                }

                Config::set('app.name', $setting->company_name);


            }
        } catch (\Exception $e) {
        }


        $app = App::getInstance();
        $app->register('Illuminate\Mail\MailServiceProvider');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
