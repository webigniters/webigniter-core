<?php

namespace Webigniter\Controllers;

use App\Controllers\BaseController;

class Authentication extends BaseController
{
    public function login(): string
    {
        return view('\Webigniter\Views\login');
    }

}