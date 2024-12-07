<?php

namespace App\Controllers;

class IndexController extends BaseController
{
    public function index(): string
    {
        return view('index3');
    }
}
