<?php

namespace EHXDonate\Services;

class DonationService
{
    public static function generateUsername($email)
    {
        $username = sanitize_user(current(explode('@', $email)));

        // If username exists, add number suffix
        if (username_exists($username)) {
            $i = 1;
            while (username_exists($username . $i)) {
                $i++;
            }
            $username = $username . $i;
        }

        return $username;
    }
}
