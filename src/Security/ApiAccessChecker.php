<?php

// namespace App\Security;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;

// class ApiAccessChecker extends AbstractController
// {
//     public function grantApiAccess(string $submittedToken): bool|Response
//     {
//         if ($submittedToken && $this->isCsrfTokenValid('api', $submittedToken)) {
//             return true;
//         } elseif (!$submittedToken && $this->getUser() !== null) {
//             return true;
//         }
//         return false;
//     }
// }



// src/Security/ApiAccessChecker.php
namespace App\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ApiAccessChecker
{
    public function __construct(
        private RequestStack $requestStack,
        private CsrfTokenManagerInterface $csrfTokenManager
    ) {}

    /**
     * @throws BadRequestHttpException if access is denied
     */
    public function ensureCsrfValid(string $tokenId): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $submitted = $request?->headers->get('X-CSRF-TOKEN');

        if (! $this->csrfTokenManager->isTokenValid(
            new CsrfToken($tokenId, $submitted)
        )) {
            throw new BadRequestHttpException('Invalid CSRF token');
        }
    }
}
