<?php
/**
 * Freeform for Craft
 *
 * @package       Solspace:Freeform
 * @author        Solspace, Inc.
 * @copyright     Copyright (c) 2008-2017, Solspace, Inc.
 * @link          https://solspace.com/craft/freeform
 * @license       https://solspace.com/software/license-agreement
 */

namespace Craft;

use Solspace\Freeform\Library\Configuration\CraftPluginConfiguration;
use Solspace\Freeform\Library\Integrations\AbstractIntegration;
use Solspace\Freeform\Library\Integrations\CRM\AbstractCRMIntegration;
use Solspace\Freeform\Library\Integrations\IntegrationStorageInterface;
use Solspace\Freeform\Library\Integrations\MailingLists\AbstractMailingListIntegration;
use Solspace\Freeform\Library\Logging\CraftLogger;
use Solspace\Freeform\Library\Translations\CraftTranslator;

/**
 * @property string $id
 * @property string $name
 * @property string $handle
 * @property string $type
 * @property string $class
 * @property string $accessToken
 * @property string $settings
 * @property string $forceUpdate
 * @property string $lastUpdate
 */
class Freeform_IntegrationModel extends BaseModel implements IntegrationStorageInterface
{
    /**
     * @param string $type
     *
     * @return Freeform_IntegrationModel
     */
    public static function create($type)
    {
        $model              = new Freeform_IntegrationModel();
        $model->type        = $type;
        $model->forceUpdate = true;
        $model->lastUpdate  = new DateTime();

        return $model;
    }

    /**
     * Update the access token
     *
     * @param string $accessToken
     */
    public function updateAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Update the settings that are to be stored
     *
     * @param array $settings
     */
    public function updateSettings(array $settings = [])
    {
        $this->settings = $settings;
    }

    /**
     * @return AbstractIntegration|AbstractCRMIntegration|AbstractMailingListIntegration
     * @throws Exception
     */
    public function getIntegrationObject()
    {
        switch ($this->type) {
            case Freeform_IntegrationRecord::TYPE_MAILING_LIST:
                $handler = craft()->freeform_mailingLists;
                break;

            case Freeform_IntegrationRecord::TYPE_CRM:
                $handler = craft()->freeform_crm;
                break;

            default:
                throw new Exception(Craft::t("Unknown integration type specified"));
        }

        $className = $this->class;

        /** @var AbstractIntegration $integration */
        $integration = new $className(
            $this->id,
            $this->name,
            $this->lastUpdate,
            $this->accessToken,
            $this->settings,
            new CraftLogger(),
            new CraftPluginConfiguration(),
            new CraftTranslator(),
            $handler
        );

        $integration->setForceUpdate($this->forceUpdate);

        return $integration;
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            "id"           => AttributeType::Number,
            "name"         => AttributeType::String,
            "handle"       => AttributeType::Handle,
            "type"         => AttributeType::String,
            "class"        => AttributeType::String,
            "accessToken"  => AttributeType::String,
            "settings"     => AttributeType::Mixed,
            "forceUpdate"  => AttributeType::Bool,
            "lastUpdate"   => AttributeType::DateTime,
        ];
    }
}
