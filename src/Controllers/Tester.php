<?php

namespace Webigniter\Controllers;

use App\Controllers\BaseController;

class Tester extends BaseController
{
    public function hi()
    {
        return view('\Webigniter\Views\home');
    }

}