<?php

namespace Modules\WhiteLabel\Database\Seeders;

use App\Models\CompanySetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\WhiteLabel\Helpers\VersionHelper;

if (VersionHelper::checkAppVersion('<', '2.0.0')) {
    VersionHelper::aliasClass('InvoiceShelf\Models\User', 'App\Models\User');
    VersionHelper::aliasClass('InvoiceShelf\Models\CompanySetting', 'App\Models\CompanySetting');
}



class WhiteLabelDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $company_id = User::find(1)->companies()->first()->id;

        /**
         * check if customer portal page title entry exists
         */
        if (!CompanySetting::getSetting('customer_portal_page_title', $company_id)) {
          CompanySetting::setSettings(
            ['customer_portal_page_title' => ''],
            $company_id
          );
        }

    }
}
