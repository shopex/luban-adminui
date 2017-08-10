<?php

namespace Shopex\AdminUI;

use Illuminate\Support\ServiceProvider;

class AdminUIServiceProvider extends ServiceProvider{

	// public function register(){
	// 	$this->app->singleton('Luban',function(){
	// 		$l = new Loader(config('app.etcd_addr','http://127.0.0.1:2379'));
 //            return $l;
 //        });
	// }

	public function boot()
	{
	    $this->loadViewsFrom(__DIR__.'/../views', 'adminui');

	    $this->publishes([
	        __DIR__.'/../views' => base_path('resources/views/vendor/adminui'),
	    ]);
	}
}