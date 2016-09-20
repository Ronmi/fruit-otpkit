<?php

namespace Fruit\OTPKit;

use Fruit\CryptoKit\Base32;

abstract class OTP
{
    private $secret;

    public function __construct($secret)
    {
        if (strlen($secret) !== 10) {
            // wrong secret format, throw exception, fail fast
            throw new \Exception('YOU MUST PROVIDE 10 BYTES SECRET FOR TOTP');
        }

        $this->secret = $secret;
    }
    protected function code($v)
    {
        $hash = hash_hmac('sha1', $v, $this->secret, true);
        $offset = ord($hash[19]) & 0x0f;
        $str = substr($hash, $offset, 4);
        $val = hexdec(bin2hex($str)) & 0x7fffffff;
        return $val % 1000000;
    }

    public function url($user, $issuer = '')
    {
        $q = '?secret=' . (new Base32)->encrypt($this->secret);
        if ($issuer !== '') {
            $q .= '&issuer=' . urlencode($issuer);
        }

        return 'otpauth://' . $this->getType() . '/' . $user . $q;
    }

    abstract protected function getType();
    abstract public function validate($pass);
}
