<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

class Emails
{
    private function __construct()
    {
    }

    const USER_REGISTRATION = 'user_registration';

    const WEBSITE_RELEASE = 'website_release';

    const CONTACT_REQUEST = 'contact_request';
}
