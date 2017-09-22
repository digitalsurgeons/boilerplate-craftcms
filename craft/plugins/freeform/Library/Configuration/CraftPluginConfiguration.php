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

namespace Solspace\Freeform\Library\Configuration;

class CraftPluginConfiguration implements ConfigurationInterface
{
    const CONTEXT = "freeform";

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return \Craft\craft()->config->get($key, self::CONTEXT);
    }
}
