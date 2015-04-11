<?php namespace Folklore\EloquentPicturable;

use Illuminate\Support\ServiceProvider;
use Folklore\EloquentPicturable\Models\Picture;

class PicturableServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$configPath = __DIR__.'/../../config/config.php';
		$migrationsPath = __DIR__.'/../../migrations/';
		
		//Merge config
		$this->mergeConfigFrom($configPath, 'picturable');
		
		//Publish
		$this->publishes([
			$configPath => config_path('/picturable.php')
		], 'config');
		
		$this->publishes([
			$migrationsPath => database_path('/migrations')
		], 'migrations');
	
		$app = $this->app;
		
		//Delete files when model is deleted
		Picture::deleting(function($item) use ($app)
		{
			$path = $app['config']->get('picturable.upload_path');

			$path = $path.'/'.$item->filename;
			if(file_exists($path)) {
				$app['image']->delete($path);
			}
			return true;
		});
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
