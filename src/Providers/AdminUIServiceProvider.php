<?php

namespace Shopex\AdminUI\Providers;

use Illuminate\Support\ServiceProvider;
use Shopex\AdminUI\Console\Command;

class AdminUIServiceProvider extends ServiceProvider{

	public function register(){
		Command::register();
	}

	public function boot()
	{
	    $this->loadViewsFrom(__DIR__.'/../../views', 'adminui');

	    $this->publishes([
	        __DIR__.'/../../views' => base_path('resources/views/vendor/adminui'),
	    ]);

	    $this->publishes([
	        __DIR__.'/../../assets' => base_path('resources/assets/vendor/adminui'),
	    ]);	    
	}
}