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

namespace Solspace\Freeform\Library\Integrations\MailingLists\Implementations;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use Solspace\Freeform\Library\Exceptions\Integrations\IntegrationException;
use Solspace\Freeform\Library\Integrations\DataObjects\FieldObject;
use Solspace\Freeform\Library\Integrations\IntegrationStorageInterface;
use Solspace\Freeform\Library\Integrations\MailingLists\AbstractMailingListIntegration;
use Solspace\Freeform\Library\Integrations\MailingLists\DataObjects\ListObject;
use Solspace\Freeform\Library\Integrations\SettingBlueprint;
use Solspace\Freeform\Library\Logging\LoggerInterface;

class MailChimp extends AbstractMailingListIntegration
{
    const SETTING_API_KEY     = 'api_key';
    const SETTING_DATA_CENTER = 'data_center';

    const TITLE        = 'MailChimp';
    const LOG_CATEGORY = 'MailChimp';

    /**
     * Returns a list of additional settings for this integration
     * Could be used for anything, like - AccessTokens
     *
     * @return SettingBlueprint[]
     */
    public static function getSettingBlueprints()
    {
        return [
            new SettingBlueprint(
                SettingBlueprint::TYPE_TEXT,
                self::SETTING_API_KEY,
                'API Key',
                'Enter your MailChimp API key here.',
                true
            ),
            new SettingBlueprint(
                SettingBlueprint::TYPE_INTERNAL,
                self::SETTING_DATA_CENTER,
                'Data Center',
                'This will be fetched automatically upon authorizing your credentials.',
                false
            ),
        ];
    }

    /**
     * Check if it's possible to connect to the API
     *
     * @return bool
     */
    public function checkConnection()
    {
        $client = new Client();

        try {
            $request = $client->get($this->getEndpoint('/'));
            $request->setAuth('mailchimp', $this->getAccessToken());
            $response = $request->send();

            $json = json_decode($response->getBody(true));

            if (isset($json->error) && !empty($json->error)) {
                return false;
            }

            return isset($json->account_id) && !empty($json->account_id);
        } catch (BadResponseException $e) {
            $this->getLogger()->log(LoggerInterface::LEVEL_ERROR, $e->getMessage(), self::LOG_CATEGORY);

            return false;
        }
    }

    /**
     * Push emails to a specific mailing list for the service provider
     *
     * @param ListObject $mailingList
     * @param array      $emails
     * @param array      $mappedValues
     *
     * @return bool
     * @throws IntegrationException
     */
    public function pushEmails(ListObject $mailingList, array $emails, array $mappedValues)
    {
        $client   = new Client();
        $endpoint = $this->getEndpoint("lists/{$mailingList->getId()}");

        try {
            $members = [];
            foreach ($emails as $email) {
                $memberData = [
                    'email_address' => $email,
                    'status'        => 'subscribed',
                ];

                if (!empty($mappedValues)) {
                    $memberData['merge_fields'] = $mappedValues;
                }

                $members[] = $memberData;
            }

            $data = ['members' => $members, 'update_existing' => true];

            $request = $client->post($endpoint);
            $request->setAuth('mailchimp', $this->getAccessToken());
            $request->setHeader('Content-Type', 'application/json');
            $request->setBody(json_encode($data));
            $response = $request->send();
        } catch (BadResponseException $e) {
            $responseBody = $e->getResponse()->getBody(true);

            $this->getLogger()->log(LoggerInterface::LEVEL_ERROR, $responseBody, self::LOG_CATEGORY);
            $this->getLogger()->log(LoggerInterface::LEVEL_ERROR, $e->getMessage(), self::LOG_CATEGORY);

            throw new IntegrationException(
                $this->getTranslator()->translate('Could not connect to API endpoint')
            );
        }

        $status = $response->getStatusCode();
        if ($status !== 200) {
            $this->getLogger()->log(LoggerInterface::LEVEL_ERROR, 'Could not add emails to lists', self::LOG_CATEGORY);

            throw new IntegrationException(
                $this->getTranslator()->translate('Could not add emails to lists')
            );
        }

        $jsonResponse = json_decode($response->getBody(true));
        if (isset($jsonResponse->error_count) && $jsonResponse->error_count > 0) {
            $this->getLogger()->log(
                LoggerInterface::LEVEL_ERROR,
                json_encode($jsonResponse->errors),
                self::LOG_CATEGORY
            );

            throw new IntegrationException(
                $this->getTranslator()->translate('Could not add emails to lists')
            );
        }

        return $status === 200;
    }

    /**
     * A method that initiates the authentication
     */
    public function initiateAuthentication()
    {
    }

    /**
     * Authorizes the application
     * Returns the access_token
     *
     * @return string
     * @throws IntegrationException
     */
    public function fetchAccessToken()
    {
        return $this->getSetting(self::SETTING_API_KEY);
    }

    /**
     * Perform anything necessary before this integration is saved
     *
     * @param IntegrationStorageInterface $model
     *
     * @throws IntegrationException
     */
    public function onBeforeSave(IntegrationStorageInterface $model)
    {
        if (preg_match('/([a-zA-Z]+[0-9]+)$/', $this->getSetting(self::SETTING_API_KEY), $matches)) {
            $dataCenter = $matches[1];
            $this->setSetting(self::SETTING_DATA_CENTER, $dataCenter);
        } else {
            throw new IntegrationException('Could not detect data center for MailChimp');
        }

        $model->updateAccessToken($this->getSetting(self::SETTING_API_KEY));
        $model->updateSettings($this->getSettings());
    }

    /**
     * Makes an API call that fetches mailing lists
     * Builds ListObject objects based on the results
     * And returns them
     *
     * @return \Solspace\Freeform\Library\Integrations\MailingLists\DataObjects\ListObject[]
     * @throws IntegrationException
     */
    protected function fetchLists()
    {
        $client = new Client();
        $client->setDefaultOption(
            'query',
            [
                'fields' => 'lists.id,lists.name,lists.stats.member_count',
                'count'  => 999,
            ]
        );
        $endpoint = $this->getEndpoint('/lists');

        try {
            $request = $client->get($endpoint);
            $request->setAuth('mailchimp', $this->getAccessToken());
            $response = $request->send();
        } catch (BadResponseException $e) {
            $responseBody = $e->getResponse()->getBody(true);

            $this->getLogger()->log(LoggerInterface::LEVEL_ERROR, $responseBody, self::LOG_CATEGORY);
            $this->getLogger()->log(LoggerInterface::LEVEL_ERROR, $e->getMessage(), self::LOG_CATEGORY);

            throw new IntegrationException(
                $this->getTranslator()->translate('Could not connect to API endpoint')
            );
        }

        $status = $response->getStatusCode();
        if ($status !== 200) {
            throw new IntegrationException(
                $this->getTranslator()->translate(
                    'Could not fetch {serviceProvider} lists',
                    ['serviceProvider' => $this->getServiceProvider()]
                )
            );
        }

        $json = json_decode($response->getBody(true));

        $lists = [];
        if (isset($json->lists)) {
            foreach ($json->lists as $list) {
                if (isset($list->id, $list->name)) {
                    $lists[] = new ListObject(
                        $this,
                        $list->id,
                        $list->name,
                        $this->fetchFields($list->id),
                        $list->stats->member_count
                    );
                }
            }
        }

        return $lists;
    }

    /**
     * Fetch all custom fields for each list
     *
     * @param string $listId
     *
     * @return FieldObject[]
     * @throws IntegrationException
     */
    protected function fetchFields($listId)
    {
        $client = new Client();
        $client->setDefaultOption('query', ['count'  => 999]);

        $endpoint = $this->getEndpoint("/lists/$listId/merge-fields");

        try {
            $request = $client->get($endpoint);
            $request->setAuth('mailchimp', $this->getAccessToken());
            $response = $request->send();
        } catch (BadResponseException $e) {
            $responseBody = $e->getResponse()->getBody(true);

            $this->getLogger()->log(LoggerInterface::LEVEL_ERROR, $responseBody, self::LOG_CATEGORY);
            $this->getLogger()->log(LoggerInterface::LEVEL_ERROR, $e->getMessage(), self::LOG_CATEGORY);

            throw new IntegrationException(
                $this->getTranslator()->translate('Could not connect to API endpoint')
            );
        }

        $json = json_decode($response->getBody(true));

        if (isset($json->merge_fields)) {
            $fieldList = [];
            foreach ($json->merge_fields as $field) {
                switch ($field->type) {
                    case 'text':
                    case 'website':
                    case 'dropdown':
                        $type = FieldObject::TYPE_STRING;
                        break;

                    case 'number':
                    case 'phone':
                        $type = FieldObject::TYPE_NUMERIC;
                        break;

                    default:
                        $type = null;
                        break;
                }

                if (null === $type) {
                    continue;
                }

                $fieldList[] = new FieldObject(
                    $field->tag,
                    $field->name,
                    $type,
                    $field->required
                );
            }

            return $fieldList;
        }

        return [];
    }

    /**
     * Returns the API root url without endpoints specified
     *
     * @return string
     * @throws IntegrationException
     */
    protected function getApiRootUrl()
    {
        $dataCenter = $this->getSetting(self::SETTING_DATA_CENTER);

        if (empty($dataCenter)) {
            throw new IntegrationException(
                $this->getTranslator()->translate('Could not detect data center for MailChimp')
            );
        }

        return "https://$dataCenter.api.mailchimp.com/3.0/";
    }
}
