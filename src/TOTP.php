<?php

namespace Fruit\OTPKit;

use Fruit\CryptoKit\Base32;

/**
 * This class implements a time-based one-time password using Google Authenticator.
 *
 *
 */
class TOTP extends OTP
{
    private $window;

    public function __construct($bin, $size = 0)
    {
        parent::__construct($bin);
        $this->window = (int)$size
        if ($this->window < 0) {
            $this->window = 0;
        }
    }

    public function getType()
    {
        return 'totp';
    }

    public function validate($pass)
    {
        if (!ctype_digit($pass) or strlen($pass) !== 6) {
            return false;
        }
        $pass = (int)$pass;
        $t = (int)(time() / 30);
        $delta = (int)($this->window / 2);

        for ($i = $t - $delta; $i <= $t + $delta; $i++) {
            $str = hex2bin(sprintf('%016x', $i));
            $code = self::code($str);

            if ($pass === $code) {
                return true;
            }
        }
        return false;
    }
}
