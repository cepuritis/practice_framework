<?php
namespace Tests;

use Core\Security\CsrfTokenManager;
use PHPUnit\Framework\TestCase;
use Random\RandomException;
use Tests\Fixtures\Session\MockSessionStorage;
use Tests\Fixtures\Utils\MockSystemClock;

class CsrfTest extends TestCase
{
    /**
     * @throws RandomException
     */
    public function testCsrfTokenWorksAsExpected()
    {

        /**
         * @var MockSessionStorage $session
         */
        $session = app()->make(MockSessionStorage::class);

        /**
         * @var MockSystemClock $clock
         */
        $clock = app()->make(MockSystemClock::class);

        /**
         * We generate a new instance on each clock change
         * as we don't want to public expose the "checkForExpiryAndRegenerate" which runs in constructor
         */

        $csrfTokenManager = new CsrfTokenManager($session, $clock, CSRF_TOKEN_BITS);

        $firstToken = $csrfTokenManager->getToken();

        $this->assertNotNull($firstToken);
        //Will Generate a new token but keep the old one for 'grace period'
        $this->assertTrue($csrfTokenManager->isTokenValid($firstToken));
        $secondToken = $csrfTokenManager->getToken();
        $csrfTokenManager = new CsrfTokenManager($session, $clock, CSRF_TOKEN_BITS);

        // First one still should be valid (we now have 2 tokens)
        $this->assertTrue($csrfTokenManager->isTokenValid($firstToken));
        // A Third one is generated here as second is set to expire after grace period
        $this->assertTrue($csrfTokenManager->isTokenValid($secondToken));
        // First token is still valid here but removed after validation as no more than 2 tokens are allowed
        $this->assertTrue($csrfTokenManager->isTokenValid($firstToken));
        $csrfTokenManager = new CsrfTokenManager($session, $clock, CSRF_TOKEN_BITS);
        $thirdToken = $csrfTokenManager->getToken();
        $this->assertFalse($csrfTokenManager->isTokenValid($firstToken));
        $this->assertTrue($csrfTokenManager->isTokenValid($secondToken));

        $clock->advance(CsrfTokenManager::GRACE_PERIOD + 1);

        $csrfTokenManager = new CsrfTokenManager($session, $clock, CSRF_TOKEN_BITS);
        $this->assertFalse($csrfTokenManager->isTokenValid($secondToken));


        //This passes Lifetime + Grace period as we advanced an hour already
        $clock->advance(CsrfTokenManager::TOKEN_LIFETIME);
        $csrfTokenManager = new CsrfTokenManager($session, $clock, CSRF_TOKEN_BITS);
        $this->assertFalse($csrfTokenManager->isTokenValid($thirdToken));
    }

    /**
     * @throws RandomException
     */
    public function testCsrfMaxTokens()
    {
        /**
         * @var MockSessionStorage $session
         */
        $session = app()->make(MockSessionStorage::class);

        /**
         * @var MockSystemClock $clock
         */
        $clock = app()->make(MockSystemClock::class);

        /**
         * We generate a new instance on each clock change
         * as we don't want to public expose the "checkForExpiryAndRegenerate" which runs in constructor
         */

        $csrfTokenManager = new CsrfTokenManager($session, $clock, CSRF_TOKEN_BITS);

        $firstToken = $csrfTokenManager->getToken();

        $csrfTokenManager->isTokenValid($firstToken);

        $secondToken = $csrfTokenManager->getToken();

        $csrfTokenManager->isTokenValid($secondToken);

        $thirdToken = $csrfTokenManager->getToken();

        $this->assertTrue($csrfTokenManager->isTokenValid($secondToken));
        $this->assertTrue($csrfTokenManager->isTokenValid($thirdToken));

        $fourthToken = $csrfTokenManager->getToken();
        $csrfTokenManager = new CsrfTokenManager($session, $clock, CSRF_TOKEN_BITS);

        $this->assertFalse($csrfTokenManager->isTokenValid($firstToken));
        $this->assertTrue($csrfTokenManager->isTokenValid($secondToken));
        $this->assertFalse($csrfTokenManager->isTokenValid($secondToken));
        $this->assertTrue($csrfTokenManager->isTokenValid($fourthToken));
    }
}
