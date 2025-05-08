<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SecretaireController extends Controller
{
    //
    public function Home(){
        return Inertia::render('Secretaire/Home');
    }


    public function V_Dashboard(){
        return Inertia::render('Secretaire/Dashboard');
    }
}
