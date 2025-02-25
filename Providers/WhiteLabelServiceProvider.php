<?php

namespace Modules\WhiteLabel\Providers;

use App\Events\ModuleDisabledEvent;
use App\Services\Module\ModuleFacade;
use Illuminate\Support\ServiceProvider;
use Modules\WhiteLabel\Listeners\ModuleDisabledListener;
use Modules\WhiteLabel\Helpers\VersionHelper;

if (VersionHelper::checkAppVersion('<', '2.0.0')) {
    VersionHelper::aliasClass('InvoiceShelf\Events\ModuleDisabledEvent', 'App\Events\ModuleDisabledEvent');
    VersionHelper::aliasClass('InvoiceShelf\Services\Module\ModuleFacade', 'App\Services\Module\ModuleFacade');
}

class WhiteLabelServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $moduleName = 'WhiteLabel';

    /**
     * @var string
     */
    protected $moduleNameLower = 'whitelabel';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerMenu();
        $this->registerPublicFiles();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['events']->listen(ModuleDisabledEvent::class, ModuleDisabledListener::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower.'.php'),
        ], 'config');

        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }

    public function registerMenu()
    {
        $data = [
            'title' => 'white_label.white_label_title',
            'group' => '',
            'name' => 'White Label',
            'link' => '/admin/settings/white-label',
            'icon' => 'TagIcon',
            'owner_only' => true,
            'ability' => '',
            'model' => ''
        ];

        \Menu::make('setting_menu', function ($menu) use ($data) {
            $menu->add($data['title'], $data['link'])
                ->data('icon', $data['icon'])
                ->data('name', $data['name'])
                ->data('owner_only', $data['owner_only'])
                ->data('ability', $data['ability'])
                ->data('model', $data['model'])
                ->data('group', $data['group']);
        });
    }

    /**
     * Register public files.
     *
     * @return void
     */
    protected function registerPublicFiles()
    {
        ModuleFacade::script('whitelabel', __DIR__.'/../dist/whitelabel.umd.js');
        ModuleFacade::style('whitelabel', __DIR__.'/../dist/style.css');
    }
}
