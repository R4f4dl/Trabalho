<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController
{
    public function index()
    {
        // Aqui você define o que a rota /home vai fazer
        return view('Index.Index'); // carrega a view resources/views/home.blade.php
    }
}
