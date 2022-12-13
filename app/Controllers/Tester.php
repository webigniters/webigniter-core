<?php

namespace Webigniters\Controllers;

use App\Controllers\BaseController;

class Tester extends BaseController
{
    public function hi()
    {
        return view('home');
    }

}