<?php

namespace App\Services;

use App\Invitation;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InviteService
{
    /**
     * Create a new invitation and return its code.
     */
    public function invite(string $email, int $userId): string
    {
        $code = Str::random(32);

        Invitation::create([
            'code'       => $code,
            'email'      => $email,
            'user_id'    => $userId,
            'status'     => 'pending',
            'valid_till' => Carbon::now()->addDays(30),
        ]);

        return $code;
    }

    /**
     * Retrieve an invitation by code.
     */
    public function get(string $code): ?Invitation
    {
        return Invitation::where('code', $code)->first();
    }

    /**
     * Check if a code is valid for the given email address.
     */
    public function isAllowed(string $code, string $email): bool
    {
        return Invitation::where('code', $code)
            ->where('email', $email)
            ->where('status', 'pending')
            ->where('valid_till', '>', now())
            ->exists();
    }

    /**
     * Mark an invitation as successfully consumed.
     */
    public function consume(string $code): void
    {
        Invitation::where('code', $code)->update(['status' => 'successful']);
    }

    /**
     * Check if an invitation code exists and is still pending/valid.
     */
    public function isValid(string $code): bool
    {
        return Invitation::where('code', $code)
            ->where('status', 'pending')
            ->where('valid_till', '>', now())
            ->exists();
    }

    /**
     * Return the status string for an invitation code.
     */
    public function status(string $code): ?string
    {
        $invitation = Invitation::where('code', $code)->first();
        return $invitation?->status;
    }
}
