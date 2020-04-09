<?php
namespace App;

use App\Security\ForbiddenException;

class Auth {

    public static function check() {
        if (!isset($_SESSION['auth'])) {
            throw new ForbiddenException();
        }
    }

}