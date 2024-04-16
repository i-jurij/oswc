<?php

namespace App\Controllers;

final class NotFound404
{
    public function index()
    {
        include_once APPROOT.DS.'view'.DS.'404_not_found_page_with_header.php';
    }
}
