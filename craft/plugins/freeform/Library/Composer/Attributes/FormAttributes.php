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

namespace Solspace\Freeform\Library\Composer\Attributes;

use Solspace\Freeform\Library\Helpers\HashHelper;
use Solspace\Freeform\Library\Session\FormValueContext;
use Solspace\Freeform\Library\Session\RequestInterface;
use Solspace\Freeform\Library\Session\SessionInterface;

class FormAttributes
{
    /** @var int */
    private $id;

    /** @var bool */
    private $csrfEnabled;

    /** @var string */
    private $csrfToken;

    /** @var string */
    private $csrfTokenName;

    /** @var string */
    private $actionUrl;

    /** @var string */
    private $method;

    /** @var FormValueContext */
    private $formValueContext;

    /**
     * FormAttributes constructor.
     *
     * @param                  $formId
     * @param SessionInterface $session
     * @param RequestInterface $request
     */
    public function __construct($formId, SessionInterface $session, RequestInterface $request)
    {
        $this->id     = $formId;
        $this->method = "POST";
        $this->setFormValueContext($session, $request);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function isCsrfEnabled()
    {
        return $this->csrfEnabled;
    }

    /**
     * @param boolean $csrfEnabled
     *
     * @return $this
     */
    public function setCsrfEnabled($csrfEnabled)
    {
        $this->csrfEnabled = $csrfEnabled;

        return $this;
    }

    /**
     * @return string
     */
    public function getCsrfToken()
    {
        return $this->csrfToken;
    }

    /**
     * @param string $csrfToken
     *
     * @return $this
     */
    public function setCsrfToken($csrfToken)
    {
        $this->csrfToken = $csrfToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getCsrfTokenName()
    {
        return $this->csrfTokenName;
    }

    /**
     * @param string $csrfTokenName
     *
     * @return $this
     */
    public function setCsrfTokenName($csrfTokenName)
    {
        $this->csrfTokenName = $csrfTokenName;

        return $this;
    }

    /**
     * @return string
     */
    public function getActionUrl()
    {
        return $this->actionUrl;
    }

    /**
     * @param string $actionUrl
     *
     * @return $this
     */
    public function setActionUrl($actionUrl)
    {
        $this->actionUrl = $actionUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return FormValueContext
     */
    public function getFormValueContext()
    {
        return $this->formValueContext;
    }

    /**
     * @param SessionInterface $session
     * @param RequestInterface $request
     *
     * @return FormValueContext
     */
    private function setFormValueContext(SessionInterface $session, RequestInterface $request)
    {
        $hashPrefix = HashHelper::hash($this->id);

        $this->formValueContext = $session->get(
            $hashPrefix . "_form_context",
            new FormValueContext($this->getId(), $session, $request)
        );
    }
}
