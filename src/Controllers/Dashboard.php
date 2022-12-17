<?php

namespace Webigniter\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index(): string
    {
        return view('\Webigniter\Views\dashboard');
    }

}