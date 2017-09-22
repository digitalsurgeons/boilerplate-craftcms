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

namespace Solspace\Freeform\Library\Helpers;

use Hashids\Hashids;

class HashHelper
{
    const SALT       = "composer";
    const MIN_LENGTH = 9;

    /** @var Hashids */
    private static $hashids;

    /**
     * @param int $id
     *
     * @return string
     */
    public static function hash($id)
    {
        return self::getHashids()->encode($id);
    }

    /**
     * @param string $hash
     *
     * @return int
     */
    public static function decode($hash)
    {
        $idList = self::getHashids()->decode($hash);

        return array_pop($idList);
    }

    /**
     * @param string $hash
     *
     * @return array
     */
    public static function decodeMultiple($hash)
    {
        return self::getHashids()->decode($hash);
    }

    /**
     * @param mixed $value
     * @param int   $length
     * @param int   $offset
     *
     * @return string
     */
    public static function sha1($value, $length = null, $offset = 0)
    {
        $hash = sha1($value);

        if ($length) {
            return substr($hash, $offset, $length);
        }

        return $hash;
    }

    /**
     * @return Hashids
     */
    private static function getHashids()
    {
        if (is_null(self::$hashids)) {
            self::$hashids = new Hashids(self::SALT, self::MIN_LENGTH);
        }

        return self::$hashids;
    }
}
