# Laravel Locality

It is a tool to perform following tasks -
- Manage servie providers and aliases for local environment
- Make .editorconfig file

## Requirements
- PHP 5.6.*
- Laravel 5.*

## Usage

### Step 1: Install through Composer
You need to set the `minimum_stability` to `dev` to use the package.
`composer require munza/locality`

### Step 2: Add the Service Provider
Add the following into your `config/app.php` file.
`Munza\Locality\Providers\ServiceProvider::class`

### Step 3: Publish config files
Now publish the config file by running following Artisan Command into your `config/locality.php` file. Add providers and aliases that will only run in local environment.
`php artisan vendor:publish --provider="Munza\Locality\Providers\LocalityServiceProvider"`

### Step 4: EditorConfig
Now create an `.editorconfig` file by running the following Artisan Command.
`php artisan make:editorconfig`
But if you want the default configuration then use -
`php artisan make:editorconfig --default`


## License
This package is open-sourced software licensed under the MIT license.
