<?php

namespace Shopex\AdminUI;

use Illuminate\Support\ServiceProvider;

class AdminUIServiceProvider extends ServiceProvider{

	public function register(){
		Command::register();
	}

	public function boot()
	{
	    $this->loadViewsFrom(__DIR__.'/../views', 'adminui');

	    $this->publishes([
	        __DIR__.'/../views' => base_path('resources/views/vendor/adminui'),
	    ]);
	}
}