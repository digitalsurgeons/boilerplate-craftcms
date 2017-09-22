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

namespace Solspace\Freeform\Library\Composer\Components\Fields\Interfaces;

use Solspace\Freeform\Library\Composer\Components\Fields\DataContainers\Option;

interface OptionsInterface
{
    /**
     * @return Option[]
     */
    public function getOptions();
}
