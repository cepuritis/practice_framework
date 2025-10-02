<?php

namespace Core\Security;

use Core\Contracts\Session\SessionStorageInterface;
use Core\Contracts\Utils\ClockInterface;
use Random\RandomException;

/**
 * We can have multiple tokens but only the newest is served when calling getToken()
 * The consumed ones have an expiry time of 1h so they are still valid
 *
 * Every time the newest token is consumed a new one is generated
 * There can only be max 3 tokens at one time the oldest gets removed past that
 */
class CsrfTokenManager
{
    public const TOKEN_KEY = 'csrf_token';
    public const TOKEN_LIFETIME = 86400;
    public const GRACE_PERIOD = 3600;

    public const MAX_TOKENS = 3;
    private int $byteCount;
    private SessionStorageInterface $session;
    private ClockInterface $time;

    /**
     * @param SessionStorageInterface $session
     * @param ClockInterface $time
     * @param int $bits
     * @throws RandomException
     */
    public function __construct(SessionStorageInterface $session, ClockInterface $time, int $bits)
    {
        if ($bits % 8 !== 0 || $bits < 128) {
            throw new \InvalidArgumentException("Token size must be a multiple of 8 and at least 128 bits.");
        }

        $this->time = $time;
        $this->byteCount = $bits / 8;
        $this->session = $session;

        if (!$session->get(self::TOKEN_KEY)) {
            $session->set(self::TOKEN_KEY, $this->generateTokenEntry());
        } else {
            $this->checkIfSizeChangedAndRegenerate();
            $this->checkForExpiryAndRegenerate();
        }
    }


    /**
     * @return string
     * @throws RandomException
     */
    protected function createToken(): string
    {
        return bin2hex(random_bytes($this->byteCount));
    }

    /**
     * @return array<string, int>
     * @throws RandomException
     */
    protected function generateTokenEntry(): array
    {
        return [$this->createToken() => $this->time->now() + self::TOKEN_LIFETIME];
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
     * Remove the old token only if it's been more than 1 hour after expiry
     * or if we overreached MAX_TOKENS
     *
     * @return void
     * @throws RandomException
     */
    protected function checkForExpiryAndRegenerate(): void
    {
        $time = $this->time->now();
        if (!($tokens = $this->session->get(self::TOKEN_KEY))) {
            return ;
        }

        $hasNewestExpired = false;
        if (count($tokens) > self::MAX_TOKENS) {
            array_shift($tokens);
        }

        foreach ($tokens as $token => $expiry) {
            $hasNewestExpired = false;
            if ($time > $expiry) {
                $hasNewestExpired = true;
            }

            if ($time > $expiry + self::GRACE_PERIOD) {
                unset($tokens[$token]);
            }
        }

        if ($hasNewestExpired) {
            $tokens = array_merge($tokens, $this->generateTokenEntry());
        }

        $this->session->set(self::TOKEN_KEY, $tokens);
    }

    /**
     * @return string | null
     */
    public function getToken(): ?string
    {
        if (!($tokens = $this->session->get(self::TOKEN_KEY))) {
            return null;
        }

        return array_key_last($tokens);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isTokenValid(string $value): bool
    {
        if (!($tokens = $this->session->get(self::TOKEN_KEY))) {
            return false;
        }

        $newestToken = array_key_last($tokens);

        $reversed = array_reverse($tokens);

        $i = 0;
        foreach ($reversed as $token => $timestamp) {
            $i++;
            if ($token === $value) {
                if ($token == $newestToken) {
                    $tokens[$token] = $this->time->now();
                    $tokens = array_merge($tokens, $this->generateTokenEntry());
                } elseif ($i > self::MAX_TOKENS - 1) {
                    unset($tokens[$token]);
                }
                $this->session->set(self::TOKEN_KEY, $tokens);
                return true;
            }
        }

        return false;
    }
}
