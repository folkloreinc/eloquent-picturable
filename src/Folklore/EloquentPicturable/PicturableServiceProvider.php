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
		$this->package('folklore/eloquent-picturable','eloquent-picturable');
		
		$app = $this->app;
		
		//Delete files when model is deleted
		Picture::deleting(function($item) use ($app)
		{
			$path = $app['config']->get('eloquent-picturable::upload_path');

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
