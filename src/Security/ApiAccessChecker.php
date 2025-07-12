<?php
namespace App\Security;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ApiAccessChecker
{
    public function __construct(
        private CsrfTokenManagerInterface $csrfTokenManager
    ) {}

    /**
     * @throws BadRequestHttpException if access is denied
     */
    public function ensureCsrfValid(string $tokenId, ?string $submitted): void
    {
        if (! $this->csrfTokenManager->isTokenValid(
            new CsrfToken($tokenId, $submitted)
        )) {
            throw new BadRequestHttpException('Invalid CSRF token');
        }
    }
}
