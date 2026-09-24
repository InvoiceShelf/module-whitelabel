<?php

namespace Modules\WhiteLabel\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\WhiteLabel\Helpers\VersionHelper;

if (VersionHelper::checkAppVersion('<', '2.0.0')) {
    VersionHelper::aliasClass('InvoiceShelf\Models\CompanySetting', 'App\Models\CompanySetting');
    VersionHelper::aliasClass('InvoiceShelf\Models\Setting', 'App\Models\Setting');
}

class UploadLogoController extends Controller
{
    /**
     * Logos are images of the types InvoiceShelf itself accepts for avatars,
     * stored on the public disk, which is what /storage serves. The default
     * disk is whichever File Disk the administrator made default and need not
     * be public at all.
     */
    private const LOGO_RULES = ['nullable', 'file', 'mimes:gif,jpg,png', 'max:20000'];

    public function uploadLogos(Request $request)
    {
        Gate::authorize('owner only');

        $request->validate([
            'customer_portal_logo' => self::LOGO_RULES,
            'admin_portal_logo' => self::LOGO_RULES,
            'login_page_logo' => self::LOGO_RULES,
        ]);

        $adminPortalLogoUrl = null;
        $customerPortalLogoUrl = null;
        $loginPageLogoUrl = null;

        if ($request->hasFile('customer_portal_logo')) {
            $imageName = time().'.'.$request->customer_portal_logo->extension();
            $request->customer_portal_logo->storeAs('whitelabel/customer_portal_logo', $imageName, 'public');

            $customerPortalLogoUrl = 'whitelabel/customer_portal_logo/'.$imageName;

            $settings = [
                'customer_portal_logo' => $customerPortalLogoUrl,
            ];

            CompanySetting::setSettings($settings, $request->header('company'));
        }

        if ($request->hasFile('admin_portal_logo')) {
            $imageName = time().'.'.$request->admin_portal_logo->extension();
            $request->admin_portal_logo->storeAs('whitelabel/admin_portal_logo', $imageName, 'public');

            $adminPortalLogoUrl = 'whitelabel/admin_portal_logo/'.$imageName;

            Setting::setSetting('admin_portal_logo', $adminPortalLogoUrl);
        }

        if ($request->hasFile('login_page_logo')) {
            $imageName = time().'.'.$request->login_page_logo->extension();
            $request->login_page_logo->storeAs('whitelabel/login_page_logo', $imageName, 'public');

            $loginPageLogoUrl = 'whitelabel/login_page_logo/'.$imageName;

            Setting::setSetting('login_page_logo', $loginPageLogoUrl);
        }

        return response()->json([
            'success' => true,
            'customerPortalLogoUrl' => $customerPortalLogoUrl,
            'adminPortalLogoUrl' => $adminPortalLogoUrl,
            'loginPageLogoUrl' => $loginPageLogoUrl,
        ], 200);
    }
}
