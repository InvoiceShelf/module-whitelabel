<?php

namespace Modules\WhiteLabel\Listeners;

use App\Events\ModuleDisabledEvent;
use App\Models\Company;
use App\Models\Setting;
use Modules\WhiteLabel\Helpers\VersionHelper;

if (VersionHelper::checkAppVersion('<', '2.0.0')) {
    VersionHelper::aliasClass('InvoiceShelf\Events\ModuleDisabledEvent', 'App\Events\ModuleDisabledEvent');
    VersionHelper::aliasClass('InvoiceShelf\Models\Company', 'App\Models\Company');
    VersionHelper::aliasClass('InvoiceShelf\Models\Setting', 'App\Models\Setting');
}

class ModuleDisabledListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ModuleDisabledEvent $event
     * @return void
     */
    public function handle(ModuleDisabledEvent $event)
    {
        if ($event->module->name !== config('whitelabel.name')) {
            return false;
        }

        $companies = Company::all();

        $companies->map(function ($company) {
            $company->settings()->whereIn('option', ['customer_portal_logo', 'customer_portal_logo'])->delete();
            Setting::where('option', 'admin_portal_theme')->delete();
        });
    }
}
