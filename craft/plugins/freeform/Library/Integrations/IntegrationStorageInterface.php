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

namespace Solspace\Freeform\Library\Integrations;

interface IntegrationStorageInterface
{
    /**
     * Update the access token
     *
     * @param string $accessToken
     */
    public function updateAccessToken($accessToken);

    /**
     * Update the settings that are to be stored
     *
     * @param array $settings
     */
    public function updateSettings(array $settings = []);
}
