<?php namespace Formandsystem\Api;

use Illuminate\Support\ServiceProvider;
use Config;

class ApiServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
   * Booting
   */
	public function boot()
	{
		$this->package('formandsystem/api');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app->bind('api', function($app){

			// get configuration (laravel)
			$config = Config::get('api::config');

			$cache = $app->make('cache');

			return new Api($config['config'], $cache);

		});

		$this->app->booting(function()
		{
		  $loader = \Illuminate\Foundation\AliasLoader::getInstance();
		  $loader->alias('Api', 'Formandsystem\Api\Facades\Api');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('api');
	}

}
