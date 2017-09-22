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

namespace Solspace\Freeform\Library\Integrations\MailingLists;

use Solspace\Freeform\Library\Configuration\ConfigurationInterface;
use Solspace\Freeform\Library\Database\MailingListHandlerInterface;
use Solspace\Freeform\Library\Exceptions\Integrations\IntegrationException;
use Solspace\Freeform\Library\Exceptions\Integrations\ListNotFoundException;
use Solspace\Freeform\Library\Integrations\AbstractIntegration;
use Solspace\Freeform\Library\Integrations\DataObjects\FieldObject;
use Solspace\Freeform\Library\Integrations\IntegrationInterface;
use Solspace\Freeform\Library\Integrations\MailingLists\DataObjects\ListObject;
use Solspace\Freeform\Library\Logging\LoggerInterface;
use Solspace\Freeform\Library\Translations\TranslatorInterface;

abstract class AbstractMailingListIntegration extends AbstractIntegration implements MailingListIntegrationInterface, IntegrationInterface, \JsonSerializable
{
    const TYPE = "mailing_list";

    /** @var MailingListHandlerInterface */
    private $mailingListHandler;

    /**
     * AbstractMailingList constructor.
     *
     * @param int                         $id
     * @param string                      $name
     * @param \DateTime                   $lastUpdate
     * @param string                      $accessToken
     * @param array|null                  $settings
     * @param LoggerInterface             $logger
     * @param ConfigurationInterface      $configuration
     * @param TranslatorInterface         $translator
     * @param MailingListHandlerInterface $mailingListHandler
     */
    public final function __construct(
        $id,
        $name,
        \DateTime $lastUpdate,
        $accessToken,
        $settings,
        LoggerInterface $logger,
        ConfigurationInterface $configuration,
        TranslatorInterface $translator,
        MailingListHandlerInterface $mailingListHandler
    ) {
        parent::__construct(
            $id,
            $name,
            $lastUpdate,
            $accessToken,
            $settings,
            $logger,
            $configuration,
            $translator
        );

        $this->mailingListHandler = $mailingListHandler;
    }

    /**
     * @return ListObject[]
     */
    public final function getLists()
    {
        if ($this->isForceUpdate()) {
            $lists = $this->fetchLists();
            $this->mailingListHandler->updateLists($this, $lists);
        } else {
            $lists = $this->mailingListHandler->getLists($this);
        }

        return $lists;
    }

    /**
     * @param string $listId
     *
     * @return ListObject
     * @throws ListNotFoundException
     */
    public final function getListById($listId)
    {
        return $this->mailingListHandler->getListById($this, $listId);
    }

    /**
     * Makes an API call that fetches mailing lists
     * Builds ListObject objects based on the results
     * And returns them
     *
     * @return ListObject[]
     */
    protected abstract function fetchLists();

    /**
     * Fetch all custom fields for each list
     *
     * @param string $listId
     *
     * @return FieldObject[]
     * @throws IntegrationException
     */
    abstract protected function fetchFields($listId);

    /**
     * Specify data which should be serialized to JSON
     */
    public function jsonSerialize()
    {
        try {
            $lists = $this->getLists();
        } catch (\Exception $e) {
            $lists = [];
        }

        return [
            "integrationId"  => $this->getId(),
            "resourceId"     => "",
            "type"           => self::TYPE,
            "source"         => $this->getServiceProvider(),
            "name"           => $this->getName(),
            "label"          => 'Opt-in mailing list "' . $this->getName() . '"',
            "emailFieldHash" => "",
            "lists"          => $lists,
        ];
    }
}
