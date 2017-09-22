<?php

namespace Dukt\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class GoogleUser implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId()
    {
        return $this->response['id'];
    }

    /**
     * Get perferred display name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->response['name'];
    }

    /**
     * Get perferred first name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->response['given_name'];
    }

    /**
     * Get perferred last name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->response['family_name'];
    }

    /**
     * Get email address.
     *
     * @return string|null
     */
    public function getEmail()
    {
        if (!empty($this->response['email'])) {
            return $this->response['email'];
        }
    }

    /**
     * Get avatar image URL.
     *
     * @return string|null
     */
    public function getAvatar()
    {
        if (!empty($this->response['picture'])) {
            return $this->response['picture'];
        }
    }

    /**
     * Get user data as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
