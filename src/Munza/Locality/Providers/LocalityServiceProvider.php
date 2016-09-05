<?php

namespace Munza\Locality\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class LocalityServiceProvider extends ServiceProvider
{
    /**
	 * Contains the list of local service providers.
	 *
	 * @var array
	 */
	protected $providers;

    /**
	 * Contains the list of local aliases.
	 *
	 * @var array
	 */
	protected $aliases;

    /**
	 * Instance of AliasLoader.
	 *
	 * @var \Illuminate\Foundation\AliasLoader
	 */
	protected $aliasLoader;

    /**
	 * Service provider constructor method.
	 *
	 * @param \Illuminate\Support\Facades\App $app
	 * @return void
	 */
	public function __construct($app)
	{
		parent::__construct($app);

		$this->providers = config('locality.providers', []);
		$this->aliases = config('locality.aliases', []);

		$this->aliasLoader = AliasLoader::getInstance();
	}

    /**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// publish the config file for the package
		$this->publishes([
			__DIR__ . '/../resources/config/config.php' => config_path('locality.php'),
		], 'config');
	}

    /**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		if ($this->runningInLocal()) {
			$this->registerProviders();
			$this->registerAliases();
            $this->registerMakeEditorConfigCommand();
		}
	}

    /**
	 * Register service providers.
	 *
	 * @return void
	 */
	private function registerProviders()
	{
		foreach ($this->providers as $provider) {
			$this->app->register($provider);
		}
	}

	/**
	 * Register aliases.
	 *
	 * @return void
	 */
	private function registerAliases()
	{
		foreach ($this->aliases as $alias => $facade) {
			$this->aliasLoader->alias($alias, $facade);
		}
	}

    /**
     * Register make editorconfig command.
     *
     * @return void
     */
    private function registerMakeEditorConfigCommand()
    {
        $this->app->singleton('command.locality.editorconfig', function ($app) {
            return $app['Munza\Locality\Commands\MakeEditorConfigCommand'];
        });
        $this->commands('command.locality.editorconfig');
    }

	/**
	 * Check if the application is running in local environment.
	 *
	 * @return void
	 */
	private function runningInLocal()
	{
		return $this->app->environment() == 'local';
	}
}
