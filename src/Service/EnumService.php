<?php

namespace App\Service;

use BackedEnum;
use ValueError;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EnumService extends AbstractController
{
    /**
     * @param string $raw
     * @param class-string<BackedEnum> $enumClass
     */
    public function enumFromString(string $raw, string $enumClass): BackedEnum
    {
        try {
            // Use the native `from()` which throws ValueError if $raw is invalid
            return $enumClass::from($raw);
        } catch (ValueError $e) {
            // Build a nice 400‐level message using the enum’s cases
            $allowed = implode(', ', array_map(fn(BackedEnum $c) => $c->value, $enumClass::cases()));
            throw new BadRequestHttpException(sprintf(
                'Invalid "%s", expected one of [%s]',
                $raw,
                $allowed
            ));
        }
    }
}
