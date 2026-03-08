<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string  invite(string $email, int $userId)
 * @method static \App\Invitation|null get(string $code)
 * @method static bool isAllowed(string $code, string $email)
 * @method static void consume(string $code)
 * @method static bool isValid(string $code)
 * @method static string|null status(string $code)
 *
 * @see \App\Services\InviteService
 */
class Invite extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'invite';
    }
}
