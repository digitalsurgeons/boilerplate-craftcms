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

use Solspace\Freeform\Library\Configuration\ConfigurationInterface;
use Solspace\Freeform\Library\Exceptions\Integrations\IntegrationException;
use Solspace\Freeform\Library\Integrations\DataObjects\FieldObject;
use Solspace\Freeform\Library\Logging\LoggerInterface;
use Solspace\Freeform\Library\Translations\TranslatorInterface;

abstract class AbstractIntegration implements IntegrationInterface
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var \DateTime */
    private $lastUpdate;

    /** @var string */
    private $accessToken;

    /** @var bool */
    private $accessTokenUpdated;

    /** @var array */
    private $settings;

    /** @var ConfigurationInterface */
    private $configuration;

    /** @var LoggerInterface */
    private $logger;

    /** @var bool */
    private $forceUpdate;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * Returns a list of additional settings for this integration
     * Could be used for anything, like - AccessTokens
     *
     * @return SettingBlueprint[]
     */
    public static function getSettingBlueprints()
    {
        return [];
    }

    /**
     * @param int                    $id
     * @param string                 $name
     * @param \DateTime              $lastUpdate
     * @param string                 $accessToken
     * @param array|null             $settings
     * @param LoggerInterface        $logger
     * @param ConfigurationInterface $configuration
     * @param TranslatorInterface    $translator
     */
    public function __construct(
        $id,
        $name,
        \DateTime $lastUpdate,
        $accessToken,
        $settings,
        LoggerInterface $logger,
        ConfigurationInterface $configuration,
        TranslatorInterface $translator
    ) {
        $this->id            = $id;
        $this->name          = $name;
        $this->lastUpdate    = $lastUpdate;
        $this->accessToken   = $accessToken;
        $this->settings      = $settings;
        $this->logger        = $logger;
        $this->configuration = $configuration;
        $this->translator    = $translator;
    }

    /**
     * Check if it's possible to connect to the API
     *
     * @return bool
     */
    abstract public function checkConnection();

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * Setting this to true will force re-fetching of all lists
     *
     * @param bool $value
     */
    public final function setForceUpdate($value)
    {
        $this->forceUpdate = (bool)$value;
    }

    /**
     * @return bool
     */
    public final function isForceUpdate()
    {
        return (bool)$this->forceUpdate;
    }

    /**
     * Returns the MailingList service provider short name
     * i.e. - MailChimp, Constant Contact, etc...
     *
     * @return string
     */
    public function getServiceProvider()
    {
        $reflection = new \ReflectionClass($this);

        return $reflection->getShortName();
    }

    /**
     * A method that initiates the authentication
     */
    abstract public function initiateAuthentication();

    /**
     * Authorizes the application
     * Returns the access_token
     *
     * @return string
     * @throws IntegrationException
     */
    abstract public function fetchAccessToken();

    /**
     * Perform anything necessary before this integration is saved
     *
     * @param IntegrationStorageInterface $model
     */
    public function onBeforeSave(IntegrationStorageInterface $model)
    {
    }

    /**
     * @return array
     */
    public final function getSettings()
    {
        return $this->settings ?: [];
    }

    /**
     * @return string
     */
    public final function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return boolean
     */
    public function isAccessTokenUpdated()
    {
        return $this->accessTokenUpdated;
    }

    /**
     * @param boolean $accessTokenUpdated
     *
     * @return $this
     */
    public function setAccessTokenUpdated($accessTokenUpdated)
    {
        $this->accessTokenUpdated = (bool)$accessTokenUpdated;

        return $this;
    }

    /**
     * @param FieldObject $fieldObject
     * @param mixed|null  $value
     *
     * @return bool|string
     */
    public function convertCustomFieldValue(FieldObject $fieldObject, $value = null)
    {
        if (is_array($value) && $fieldObject->getType() !== FieldObject::TYPE_ARRAY) {
            $value = implode(', ', $value);
        }

        switch ($fieldObject->getType()) {
            case FieldObject::TYPE_NUMERIC:
                return (int)preg_replace('/\D/', '', $value) ?: '';

            case FieldObject::TYPE_BOOLEAN:
                return (bool)$value;

            case FieldObject::TYPE_ARRAY:
                if (!is_array($value)) {
                    $value = [$value];
                }

                return $value;

            case FieldObject::TYPE_STRING:
            default:
                return (string)$value;
        }
    }

    /**
     * @param string $accessToken
     */
    protected final function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return TranslatorInterface
     */
    protected function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @return string
     */
    protected abstract function getApiRootUrl();

    /**
     * Returns a combined URL of api root + endpoint
     *
     * @param string $endpoint
     *
     * @return string
     */
    protected final function getEndpoint($endpoint)
    {
        $root     = rtrim($this->getApiRootUrl(), "/");
        $endpoint = ltrim($endpoint, "/");

        return "$root/$endpoint";
    }

    /**
     * Get settings by handle
     *
     * @param string $handle
     *
     * @return mixed|null
     * @throws IntegrationException
     */
    protected final function getSetting($handle)
    {
        $blueprint = $this->getSettingBlueprint($handle);

        if ($blueprint->getType() === SettingBlueprint::TYPE_CONFIG) {
            return $this->configuration->get($blueprint->getHandle());
        }

        if (isset($this->settings[$handle])) {
            if ($blueprint->getType() === SettingBlueprint::TYPE_BOOL) {
                return (bool) $this->settings[$handle];
            }

            return $this->settings[$handle];
        }

        if ($blueprint->isRequired()) {
            throw new IntegrationException(
                $this->getTranslator()->translate(
                    "{setting} setting not specified",
                    ["setting" => $blueprint->getLabel()]
                )
            );
        }

        return null;
    }

    /**
     * @param string $handle
     * @param mixed  $value
     *
     * @return $this
     */
    protected final function setSetting($handle, $value)
    {
        // Check for blueprint validity
        $this->getSettingBlueprint($handle);

        $this->settings[$handle] = $value;

        return $this;
    }

    /**
     * @param string $handle
     *
     * @return SettingBlueprint
     * @throws IntegrationException
     */
    private function getSettingBlueprint($handle)
    {
        foreach (static::getSettingBlueprints() as $blueprint) {
            if ($blueprint->getHandle() === $handle) {
                return $blueprint;
            }
        }

        throw new IntegrationException(
            $this->getTranslator()->translate(
                "Could not find setting blueprints for {handle}",
                ["handle" => $handle]
            )
        );
    }
}
