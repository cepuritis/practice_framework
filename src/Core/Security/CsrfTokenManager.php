<?php

namespace Core\Security;

use Core\User\Session;
use Random\RandomException;

class CsrfTokenManager
{
    public const TOKEN_KEY = 'csrf_token';
    public const TOKEN_LIFETIME = 86400;
    private int $byteCount;
    private Session $session;

    public function __construct(Session $session, int $bits)
    {
        if ($bits % 8 !== 0 || $bits < 128) {
            throw new \InvalidArgumentException("Token size must be a multiple of 8 and at least 128 bits.");
        }
        $this->byteCount = $bits / 8;
        $this->session = $session;

        if (!$session->get(self::TOKEN_KEY)) {
            $session->set(self::TOKEN_KEY, $this->generateTokenEntry());
        } else {
            $this->checkIfSizeChangedAndRegenerate();
            $this->checkForExpiryAndRegenerate();
        }

        var_dump($session->get(self::TOKEN_KEY));
    }

    /**
     * @throws RandomException
     */
    protected function createToken()
    {
        return bin2hex(random_bytes($this->byteCount));
    }

    protected function generateTokenEntry()
    {
        return [$this->createToken() => time() + self::TOKEN_LIFETIME];
    }

    /**
     * @throws RandomException
     */
    protected function checkIfSizeChangedAndRegenerate()
    {
        if ($this->session->get(self::TOKEN_KEY)
            && strlen(array_key_first($this->session->get(self::TOKEN_KEY))) != $this->byteCount * 2) {
            $this->session->set(self::TOKEN_KEY, $this->generateTokenEntry());
        }
    }

    /**
     * Generate a new token if token expired and add it to the end of array
     * Remove the old token only if its been more than 1 hour after expiry
     *
     * @return void
     * @throws RandomException
     */
    protected function checkForExpiryAndRegenerate(): void
    {
        $time = time();
        $gracePeriod = 3600;
        if (!($tokens = $this->session->get(self::TOKEN_KEY))) {
            return ;
        }

        $hasNewestExpired = false;
        foreach ($tokens as $token => $expiry) {
            $hasNewestExpired = false;
            if ($time > $expiry) {
                $hasNewestExpired = true;
            }

            if ($time > $expiry + $gracePeriod) {
                unset($tokens[$token]);
            }
        }

        if ($hasNewestExpired) {
            $tokens = array_merge($tokens, $this->generateTokenEntry());
        }

        $this->session->set(self::TOKEN_KEY, $tokens);
    }

    public function getToken()
    {
        if (!($tokens = $this->session->get(self::TOKEN_KEY))) {
            return null;
        }

        return key(end($tokens));
    }

    public function validateToken(string $value)
    {
        if (!($tokens = $this->session->get(self::TOKEN_KEY))) {
            return false;
        }

        $valid = false;
    }
}
