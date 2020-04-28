<?php

namespace Sally\VkSdk;

use Illuminate\Support\ServiceProvider;
use Sally\VkSdk\Commands;

class VkSdkServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            Commands\Create\Token\User::class,
            Commands\Create\Token\Group::class
        ]);
    }
}