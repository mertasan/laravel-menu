<?php

namespace Mertasan\Menu;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Blade;

class MenuServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected bool $defer = true;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-menu.php', 'laravel-menu');
        $this->registerSvgIcons();

        $this->app->singleton('menu', function ($app) {
            return new Menu();
        });
    }

    // Patterns and Replace string for lm-attr
    // Remove with next major version
    public const LM_ATTRS_PATTERN = '/(\s*)@lm-attrs\s*\((\$[^)]+)\)/';
    public const LM_ATTRS_REPLACE = '$1<?php $lm_attrs = $2->attr(); ob_start(); ?>';

    // Patterns and Replace string for lm-endattr
    // Remove with next major version
    public const LM_ENDATTRS_PATTERN = '/(?<!\w)(\s*)@lm-endattrs(\s*)/';
    public const LM_ENDATTRS_REPLACE = '$1<?php echo \Mertasan\Menu\Builder::mergeStatic(ob_get_clean(), $lm_attrs); ?>$2';

    /*
     * Extending Blade engine. Remove with next major version
     *
     * @deprecated
     * @return void
     */
    protected function bladeExtensions(): void
    {
        Blade::extend(function ($view, $compiler) {
            if (preg_match(self::LM_ATTRS_PATTERN, $view)) {
              \Log::debug("laravel-menu: @lm-attrs/@lm-endattrs is deprecated. Please switch to @lm_attrs and @lm_endattrs");
            }
            return preg_replace(self::LM_ATTRS_PATTERN, self::LM_ATTRS_REPLACE, $view);
        });

        Blade::extend(function ($view, $compiler) {
            return preg_replace(self::LM_ENDATTRS_PATTERN, self::LM_ENDATTRS_REPLACE, $view);
        });
    }

    /*
     * Adding custom Blade directives.
     */
    protected function bladeDirectives(): void
    {
        /*
         * Buffers the output if there's any.
         * The output will be passed to mergeStatic()
         * where it is merged with item's attributes
         */
        Blade::directive('lm_attrs', function ($expression) {
            return '<?php $lm_attrs = ' . $expression . '->attr(); ob_start(); ?>';
        });

        /*
         * Reads the buffer data using ob_get_clean()
         * and passes it to MergeStatic().
         * mergeStatic() takes the static string,
         * converts it into a normal array and merges it with others.
         */
        Blade::directive('lm_endattrs', function ($expression) {
            return '<?php echo \Mertasan\Menu\Builder::mergeStatic(ob_get_clean(), $lm_attrs); ?>';
        });

        Blade::directive('data_toggle_attribute', function ($expression) {
            return config('laravel-menu.menus.default.data_toggle_attribute');
        });
    }

    /**
     * Blade icons ui kit
     *
     * @see https://github.com/blade-ui-kit/blade-icons
     */
    private function registerSvgIcons ()
    {
        $svgPath = config("laravel-menu.config.svg_path");

        if (!is_null($svgPath)) {
            $this->callAfterResolving(\BladeUI\Icons\Factory::class, function (\BladeUI\Icons\Factory $factory) use($svgPath) {
                $factory->add('laravelMenuIcons', [
                    'path'   => $this->app->resourcePath($svgPath),
                    'prefix' => 'laravelmenu',
                ]);
            });
        }
    }

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->bladeDirectives();
        $this->bladeExtensions();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-menu');

        $this->publishes([__DIR__ . '/../config/laravel-menu.php' => config_path('laravel-menu.php')], 'laravel-menu-config');
        $this->publishes([__DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-menu')], 'laravel-menu-views');

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['menu'];
    }
}
