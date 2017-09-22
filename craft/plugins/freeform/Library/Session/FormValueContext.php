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

namespace Solspace\Freeform\Library\Session;

use Solspace\Freeform\Library\Composer\Components\Attributes\DynamicNotificationAttributes;
use Solspace\Freeform\Library\Composer\Components\Fields\SubmitField;
use Solspace\Freeform\Library\Helpers\HashHelper;

class FormValueContext implements \JsonSerializable
{
    const FORM_HASH_DELIMITER = "_";
    const FORM_HASH_KEY       = "formHash";
    const FORM_HONEYPOT_KEY   = "freeformHoneypotHashList";
    const FORM_HONEYPOT_NAME  = "form_name_handle";
    const HASH_PATTERN        = "/^(?P<formId>[a-zA-Z0-9]+)_(?P<pageIndex>[a-zA-Z0-9]+)_(?P<payload>.*)$/";

    const FORM_SESSION_TTL    = 10800; // 3 hours
    const ACTIVE_SESSIONS_KEY = "freeformActiveSessions";

    const MAX_HONEYPOT_TTL   = 10800; // 3 Hours
    const MAX_HONEYPOT_COUNT = 100;   // Limit the number of maximum honeypot values per session

    const DEFAULT_PAGE_INDEX    = 0;

    const DATA_DYNAMIC_TEMPLATE_KEY = "dynamicTemplate";

    /** @var int */
    private $formId;

    /**
     * This is a generated hash for storing and restoring state from a session
     *
     * @var string
     */
    private $hash;

    /** @var int */
    private $currentPageIndex;

    /** @var array */
    private $storedValues;

    /** @var array */
    private $customFormData;

    /** @var SessionInterface */
    private $session;

    /** @var RequestInterface */
    private $request;

    /** @var bool */
    private $formIsPosted;

    /** @var bool */
    private $pageIsPosted;

    /**
     * @param string $hash
     *
     * @return int|null
     */
    public static function getFormIdFromHash($hash)
    {
        list($formIdHash) = self::getHashParts($hash);

        return HashHelper::decode($formIdHash);
    }

    /**
     * @param string $hash
     *
     * @return int|null
     */
    public static function getPageIndexFromHash($hash)
    {
        list($_, $pageIndexHash) = self::getHashParts($hash);

        return HashHelper::decode($pageIndexHash);
    }

    /**
     * Returns an array of [formIdHash, pageIndexHash, payload]
     *
     * @param string $hash
     *
     * @return array
     */
    private static function getHashParts($hash)
    {
        if (preg_match(self::HASH_PATTERN, $hash, $matches)) {
            return [$matches["formId"], $matches["pageIndex"], $matches["payload"]];
        }

        return [null, null, null];
    }

    /**
     * SessionFormContext constructor.
     *
     * @param int              $formId
     * @param SessionInterface $session
     * @param RequestInterface $request
     */
    public function __construct(
        $formId,
        SessionInterface $session,
        RequestInterface $request
    ) {
        $this->session      = $session;
        $this->request      = $request;
        $this->formId       = $formId;
        $this->storedValues = [];

        $this->regenerateState();
        $this->hash = $this->generateHash();
        $this->cleanUpOldSessions();
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return int
     */
    public function getCurrentPageIndex()
    {
        return $this->currentPageIndex;
    }

    /**
     * @param int $currentPageIndex
     *
     * @return $this
     */
    public function setCurrentPageIndex($currentPageIndex)
    {
        $this->currentPageIndex = $currentPageIndex;

        return $this;
    }

    /**
     * @param string     $fieldName
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getStoredValue($fieldName, $default = null)
    {
        if (null === $fieldName) {
            return null;
        }

        if ($this->hasFormBeenPosted()) {
            if ($this->hasPageBeenPosted()) {
                $value = $this->request->getPost($fieldName);

                if (null !== $value) {
                    return $value;
                }

                if (isset($this->storedValues[$fieldName])) {
                    return $this->storedValues[$fieldName];
                }

                return null;
            }

            if (isset($this->storedValues[$fieldName])) {
                return $this->storedValues[$fieldName];
            }
        }

        if (is_string($default)) {
            $default = htmlspecialchars($default);
        }

        return $default;
    }

    /**
     * @return Honeypot
     */
    public function getNewHoneypot()
    {
        $honeypotList = $this->getHoneypotList();

        $newHoneypot    = new Honeypot();
        $honeypotList[] = $newHoneypot;

        $honeypotList = $this->weedOutOldHoneypots($honeypotList);
        $this->updateHoneypotList($honeypotList);

        return $newHoneypot;
    }

    /**
     * @return bool
     */
    public function isHoneypotValid()
    {
        $postValues = $this->request->getPost(null);

        foreach ($postValues as $key => $value) {
            if (strpos($key, Honeypot::NAME_PREFIX) === 0) {
                $honeypotList = $this->getHoneypotList();
                foreach ($honeypotList as $honeypot) {
                    $hasMatchingName = $key === $honeypot->getName();
                    $hasMatchingHash = $value === $honeypot->getHash();
                    if ($hasMatchingName && $hasMatchingHash) {
                        return true;
                    }
                }

                return false;
            }
        }

        return false;
    }

    /**
     * Checks whether the "PREVIOUS PAGE" button has been pressed
     *
     * @return bool
     */
    public function shouldFormWalkToPreviousPage()
    {
        if ($this->hasPageBeenPosted()) {
            return !is_null($this->request->getPost(SubmitField::PREVIOUS_PAGE_INPUT_NAME));
        }

        return false;
    }

    /**
     * @param array|null $data
     *
     * @return $this
     */
    public function setCustomFormData(array $data = null)
    {
        $this->customFormData = $data;

        return $this;
    }

    /**
     * @return DynamicNotificationAttributes|null
     */
    public function getDynamicNotificationData()
    {
        if (isset($this->customFormData[self::DATA_DYNAMIC_TEMPLATE_KEY])) {
            return new DynamicNotificationAttributes($this->customFormData[self::DATA_DYNAMIC_TEMPLATE_KEY]);
        }

        return null;
    }

    /**
     * @param array $storedValues
     *
     * @return $this
     */
    public function appendStoredValues($storedValues)
    {
        $this->storedValues = array_merge($this->storedValues, $storedValues);

        return $this;
    }

    /**
     * Advances the page index by 1
     */
    public function advanceToNextPage()
    {
        $this->currentPageIndex++;
    }

    /**
     * Walks back a single page
     */
    public function retreatToPreviousPage()
    {
        $this->currentPageIndex--;
    }

    /**
     * Save current state in session
     */
    public function saveState()
    {
        $encodedData    = json_encode($this, JSON_OBJECT_AS_ARRAY);
        $sessionHashKey = $this->getSessionHash($this->hash);

        $this->session->set($sessionHashKey, $encodedData);
        $this->appendKeyToActiveSessions($sessionHashKey);
    }

    /**
     * Removes the current key from active session list
     */
    public function cleanOutCurrentSession()
    {
        $sessionHashKey = $this->getSessionHash($this->hash);
        $this->session->remove($sessionHashKey);
    }

    /**
     * Attempts to regenerate existing state
     */
    public function regenerateState()
    {
        $sessionHash  = $this->getSessionHash();
        $sessionState = $this->session->get($sessionHash);

        if ($sessionHash && $sessionState) {
            $sessionState = json_decode($sessionState, true);

            $this->currentPageIndex = $sessionState["currentPageIndex"];
            $this->storedValues     = $sessionState["storedValues"];
            $this->customFormData   = $sessionState["customFormData"];
        } else {
            $this->currentPageIndex = self::DEFAULT_PAGE_INDEX;
            $this->storedValues     = [];
            $this->customFormData   = [];
        }
    }

    /**
     * Check if the current form has been posted
     *
     * @return bool
     */
    public function hasFormBeenPosted()
    {
        if (is_null($this->formIsPosted)) {
            $postedHash = $this->getPostedHash();

            if (is_null($postedHash)) {
                return false;
            }

            list($_, $_, $postedPayload) = self::getHashParts($postedHash);
            list($_, $_, $currentPayload) = self::getHashParts($this->hash);

            $this->formIsPosted = ($postedPayload === $currentPayload);
        }

        return $this->formIsPosted;
    }

    /**
     * Check if the current form has been posted
     *
     * @return bool
     */
    public function hasPageBeenPosted()
    {
        if (is_null($this->pageIsPosted)) {
            $postedHash = $this->getPostedHash();

            if (is_null($postedHash) || !$this->hasFormBeenPosted()) {
                return false;
            }

            list($_, $postedPageIndex, $postedPayload) = self::getHashParts($postedHash);
            list($_, $currentPageIndex, $currentPayload) = self::getHashParts($this->hash);

            $this->pageIsPosted = ($postedPageIndex === $currentPageIndex && $postedPayload === $currentPayload);
        }

        return $this->pageIsPosted;
    }

    /**
     * @return string|null
     */
    private function getPostedHash()
    {
        return $this->request->getPost(self::FORM_HASH_KEY);
    }

    /**
     * @param string $hash - provide an existing hash, otherwise takes it from POST
     *
     * @return string
     */
    private function getSessionHash($hash = null)
    {
        if (is_null($hash)) {
            $hash = $this->getPostedHash();
        }

        list($formIdHash, $_, $payload) = self::getHashParts($hash);

        if ($formIdHash === $this->hashFormId()) {
            return sprintf(
                "%s%s%s",
                $formIdHash,
                self::FORM_HASH_DELIMITER,
                $payload
            );
        }

        return null;
    }

    /**
     * Generates a random hash for identification
     *
     * @return string
     */
    private function generateHash()
    {
        // Attempt to fetch hashes from POST data
        list($formIdHash, $_, $payload) = self::getHashParts($this->getPostedHash());

        $formId = self::getFormIdFromHash($this->getPostedHash());
        $isFormIdMatching = $formId === (int) $this->formId;

        // Only generate a new hash if the content indexes don' match with the posted hash
        // Or if there is no posted hash
        $generateNew = !$isFormIdMatching || !($formIdHash && $payload);

        if ($generateNew) {
            $random  = time() . mt_rand(111, 999);
            $hash    = sha1($random);
            $payload = uniqid($hash, false);

            $formIdHash = HashHelper::hash($this->formId);
        }

        $hashedPageIndex = $this->hashPageIndex();

        return sprintf(
            '%s%s%s%s%s',
            $formIdHash,
            self::FORM_HASH_DELIMITER,
            $hashedPageIndex,
            self::FORM_HASH_DELIMITER,
            $payload
        );
    }

    /**
     * @return string
     */
    private function hashFormId()
    {
        return HashHelper::hash($this->formId);
    }

    /**
     * @return string
     */
    private function hashPageIndex()
    {
        return HashHelper::sha1($this->currentPageIndex, 4, 10);
    }

    /**
     * @return Honeypot[]
     */
    private function getHoneypotList()
    {
        $sessionHoneypotList = json_decode($this->session->get(self::FORM_HONEYPOT_KEY, "[]"), true);
        if (!empty($sessionHoneypotList)) {
            foreach ($sessionHoneypotList as $index => $unserialized) {
                $sessionHoneypotList[$index] = Honeypot::createFromUnserializedData($unserialized);
            }
        }

        return $sessionHoneypotList;
    }

    /**
     * @param array $honeypotList
     *
     * @return array
     */
    private function weedOutOldHoneypots(array $honeypotList)
    {
        $cleanList = array_filter(
            $honeypotList,
            function (Honeypot $honeypot) {
                return $honeypot->getTimestamp() > (time() - self::MAX_HONEYPOT_TTL);
            }
        );

        usort(
            $cleanList,
            function (Honeypot $a, Honeypot $b) {
                if ($a->getTimestamp() === $b->getTimestamp()) {
                    return 0;
                }

                return ($a->getTimestamp() < $b->getTimestamp()) ? 1 : -1;
            }
        );

        if (count($cleanList) > self::MAX_HONEYPOT_COUNT) {
            $cleanList = array_slice($cleanList, 0, self::MAX_HONEYPOT_COUNT);
        }

        return $cleanList;
    }

    /**
     * @param array $honeypotList
     */
    private function updateHoneypotList(array $honeypotList)
    {
        $this->session->set(self::FORM_HONEYPOT_KEY, json_encode($honeypotList));
    }

    /**
     * Cleans up all old session instances
     */
    private function cleanUpOldSessions()
    {
        $instances = $this->getActiveSessionList();

        foreach ($instances as $time => $hash) {
            if ($time < time() - self::FORM_SESSION_TTL) {
                $this->session->remove($hash);
                unset($instances[$time]);
            }
        }

        $this->session->set(self::ACTIVE_SESSIONS_KEY, $instances);
    }

    /**
     * Gets the active session list
     * [time => sessionHash, ..]
     *
     * @return array
     */
    private function getActiveSessionList()
    {
        return $this->session->get(self::ACTIVE_SESSIONS_KEY, []);
    }

    /**
     * Appends a session hash to active instances, for cleanup later
     *
     * @param string $sessionHash
     */
    private function appendKeyToActiveSessions($sessionHash)
    {
        $instances = $this->getActiveSessionList();

        $instances[time()] = $sessionHash;

        $this->session->set(self::ACTIVE_SESSIONS_KEY, $instances);
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *        which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            "currentPageIndex" => $this->currentPageIndex,
            "storedValues"     => $this->storedValues,
            "customFormData"   => $this->customFormData,
        ];
    }
}
