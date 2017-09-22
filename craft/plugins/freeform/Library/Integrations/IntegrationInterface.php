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

interface IntegrationInterface
{
    /**
     * Setting this to true will force re-fetching of all lists
     *
     * @param bool $value
     */
    public function setForceUpdate($value);

    /**
     * Check if it's possible to connect to the API
     *
     * @return bool
     */
    public function checkConnection();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return \DateTime
     */
    public function getLastUpdate();

    /**
     * Returns the integration service provider short name
     * i.e. - MailChimp, Constant Contact, Salesforce, etc...
     *
     * @return string
     */
    public function getServiceProvider();

    /**
     * Initiates the authentication process
     */
    public function initiateAuthentication();

    /**
     * Authorizes the application and fetches the access token
     *
     * @return string - access token
     */
    public function fetchAccessToken();

    /**
     * @return string
     */
    public function getAccessToken();

    /**
     * @return boolean
     */
    public function isAccessTokenUpdated();

    /**
     * @param boolean $accessTokenUpdated
     *
     * @return $this
     */
    public function setAccessTokenUpdated($accessTokenUpdated);

    /**
     * @return array
     */
    public function getSettings();
}
