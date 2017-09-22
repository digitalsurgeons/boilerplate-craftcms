<?php
/**
 * Freeform for Craft
 *
 * @package       Solspace:Freeform
 * @author        Solspace, Inc.
 * @copyright     Copyright (c) 2008-2016, Solspace, Inc.
 * @link          https://solspace.com/craft/freeform
 * @license       https://solspace.com/software/license-agreement
 */

namespace Solspace\Freeform\Library\Helpers;

use Craft\Craft;

class PermissionsHelper
{
    const PERMISSION_FORMS_ACCESS           = 'freeform-formsAccess';
    const PERMISSION_FORMS_MANAGE           = 'freeform-formsManage';
    const PERMISSION_FIELDS_ACCESS          = 'freeform-fieldsAccess';
    const PERMISSION_FIELDS_MANAGE          = 'freeform-fieldsManage';
    const PERMISSION_SETTINGS_ACCESS        = 'freeform-settingsAccess';
    const PERMISSION_SUBMISSIONS_ACCESS     = 'freeform-submissionsAccess';
    const PERMISSION_SUBMISSIONS_MANAGE     = 'freeform-submissionsManage';
    const PERMISSION_NOTIFICATIONS_ACCESS   = 'freeform-notificationsAccess';
    const PERMISSION_NOTIFICATIONS_MANAGE   = 'freeform-notificationsManage';
    const PERMISSION_EXPORT_PROFILES_ACCESS = 'freeform-exportProfilesAccess';
    const PERMISSION_EXPORT_PROFILES_MANAGE = 'freeform-exportProfilesManage';

    /**
     * Checks a given permission for the currently logged in user
     *
     * @param string $permissionName
     * @param bool   $checkForNested - see nested permissions for matching permission name root
     *
     * @return bool
     */
    public static function checkPermission($permissionName, $checkForNested = false)
    {
        $user           = \Craft\craft()->getUser();
        $permissionName = strtolower($permissionName);

        if (self::permissionsEnabled()) {
            if ($checkForNested) {
                $permissionList = \Craft\craft()->userPermissions->getPermissionsByUserId($user->getId());
                foreach ($permissionList as $permission) {
                    if (strpos($permission, $permissionName) === 0) {
                        return true;
                    }
                }
            }

            return $user->checkPermission($permissionName);
        } else {
            return self::isAdmin();
        }
    }

    /**
     * @param string $permissionName
     *
     * @return null
     * @throws \Craft\HttpException
     */
    public static function requirePermission($permissionName)
    {
        $user           = \Craft\craft()->getUser();
        $permissionName = strtolower($permissionName);

        return $user->requirePermission($permissionName);
    }

    /**
     * Fetches all nested allowed permission IDs from a nested permission set
     *
     * @param string $permissionName
     *
     * @return array
     */
    public static function getNestedPermissionIds($permissionName)
    {
        $user           = \Craft\craft()->getUser();
        $permissionName = strtolower($permissionName);
        $idList         = [];

        if (self::permissionsEnabled()) {
            $permissionList = \Craft\craft()->userPermissions->getPermissionsByUserId($user->getId());
            foreach ($permissionList as $permission) {
                if (strpos($permission, $permissionName) === 0) {
                    list($name, $id) = explode(":", $permission);

                    $idList[] = $id;
                }
            }

            return $idList;
        } else {
            return self::isAdmin();
        }
    }

    /**
     * Combines a nested permission with ID
     *
     * @param string $permissionName
     * @param int    $id
     *
     * @return string
     */
    public static function prepareNestedPermission($permissionName, $id)
    {
        return $permissionName . ":" . $id;
    }

    /**
     * Returns true if the currently logged in user is an admin
     *
     * @return bool
     */
    public static function isAdmin()
    {
        return \Craft\craft()->getUser()->isAdmin();
    }

    /**
     * @return bool
     */
    private static function permissionsEnabled()
    {
        $edition = \Craft\craft()->getEdition();

        return in_array($edition, [Craft::Pro, Craft::Client]);
    }
}

