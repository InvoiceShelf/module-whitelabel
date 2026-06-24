<?php

namespace Modules\WhiteLabel\Http\Controllers;

use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\WhiteLabel\Helpers\VersionHelper;

if (VersionHelper::checkAppVersion('<', '2.0.0')) {
    VersionHelper::aliasClass('InvoiceShelf\Models\CompanySetting', 'App\Models\CompanySetting');
    VersionHelper::aliasClass('InvoiceShelf\Models\Setting', 'App\Models\Setting');
}

class UploadLogoController extends Controller
{
    const MEDIA_CUSTOMER_PORTAL_LOGO = 'customer_portal_logo';

    const MEDIA_ADMIN_PORTAL_LOGO = 'admin_portal_logo';

    const MEDIA_LOGIN_PAGE_LOGO = 'login_page_logo';

    public function uploadLogos(Request $request)
    {
        $adminPortalLogoUrl = null;
        $customerPortalLogoUrl = null;
        $loginPageLogoUrl = null;

        $company = Company::find($request->header('company'));

        if (isset($request->is_customer_portal_logo_removed) && (bool) $request->is_customer_portal_logo_removed) {
            $company->clearMediaCollection(self::MEDIA_CUSTOMER_PORTAL_LOGO);

            $settings = [
                'customer_portal_logo' => '',
            ];

            CompanySetting::setSettings($settings, $request->header('company'));
        }

        if ($request->hasFile('customer_portal_logo')) {
            $customerPortalLogoUrl = $this->addMedia($request, self::MEDIA_CUSTOMER_PORTAL_LOGO);

            $settings = [
                'customer_portal_logo' => $customerPortalLogoUrl,
            ];

            CompanySetting::setSettings($settings, $request->header('company'));
        }

        if (isset($request->is_admin_portal_logo_removed)) {
            $company->clearMediaCollection(self::MEDIA_ADMIN_PORTAL_LOGO);
            Setting::setSetting(self::MEDIA_ADMIN_PORTAL_LOGO, $adminPortalLogoUrl);
        }

        if ($request->hasFile(self::MEDIA_ADMIN_PORTAL_LOGO)) {
            $adminPortalLogoUrl = $this->addMedia($request, self::MEDIA_ADMIN_PORTAL_LOGO);
            Setting::setSetting(self::MEDIA_ADMIN_PORTAL_LOGO, $adminPortalLogoUrl);
        }

        if (isset($request->is_login_page_logo_removed)) {
            $company->clearMediaCollection(self::MEDIA_LOGIN_PAGE_LOGO);
            Setting::setSetting(self::MEDIA_LOGIN_PAGE_LOGO, $loginPageLogoUrl);
        }

        if ($request->hasFile(self::MEDIA_LOGIN_PAGE_LOGO)) {
            $loginPageLogoUrl = $this->addMedia($request, self::MEDIA_LOGIN_PAGE_LOGO);
            Setting::setSetting(self::MEDIA_LOGIN_PAGE_LOGO, $loginPageLogoUrl);
        }

        return response()->json([
            'success' => true,
            'customerPortalLogoUrl' => $customerPortalLogoUrl,
            'adminPortalLogoUrl' => $adminPortalLogoUrl,
            'loginPageLogoUrl' => $loginPageLogoUrl,
        ], 200);
    }

    private function addMedia(Request $request, string $mediaName): string
    {
        $company = Company::find($request->header('company'));
        $imageName = time().'.'.$request->file($mediaName)->extension();

        $company->clearMediaCollection($mediaName);
        $company->addMediaFromRequest($mediaName)
            ->usingFileName($imageName)
            ->toMediaCollection($mediaName);

        $media = $company->getMedia($mediaName)->first();

        return sprintf('%s/%s', $media->id, $media->file_name);
    }
}
