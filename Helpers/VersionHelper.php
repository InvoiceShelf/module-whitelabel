<?php
/**
 * Version Helper
 *
 * This file contains a helper class for version checking in the application.
 * It provides a reusable method to compare the current application version with a specified version.
 *
 * PHP version 8.1
 *
 * @category  Helpers
 * @package   Modules\WhiteLabel\Helpers
 * @author    Rihards Simanovics <rihards.s@griffin-web.studio>
 * @copyright 2024 Rihards Simanovics
 * @license   GNU GPLv3
 * @version   GIT: <git_id>
 * @link      https://invoiceshelf.com
 */

namespace Modules\WhiteLabel\Helpers;

use App\Models\Setting;

// Temporarily alias the Setting class to access the version
if (class_exists('InvoiceShelf\Models\Setting')) {
    class_alias('InvoiceShelf\Models\Setting', 'App\Models\Setting');
}

class VersionHelper
{
    /**
     * Check the application version.
     *
     * This method checks the application version against a specified version using a comparison operator.
     * It temporarily aliases the Setting class to access the version information.
     *
     * @param string $operator The comparison operator (e.g., '<', '>=').
     * @param string $version  The version to compare against.
     *
     * @return bool True if the comparison is valid, false otherwise.
     */
    public static function checkAppVersion($operator, $version)
    {

        $appVersion = Setting::getSetting('version');

        return version_compare($appVersion, $version, $operator);
    }

    /**
     * Alias old namespace classes to new ones if necessary.
     *
     * @param string $oldClass The old class name with namespace.
     * @param string $newClass The new class name with namespace.
     */
    public static function aliasClass($newClass, $oldClass)
    {
        if (!class_exists($oldClass) && class_exists($newClass)) {
            class_alias($newClass, $oldClass);
        }
    }
}
