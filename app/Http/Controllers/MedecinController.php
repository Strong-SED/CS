<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class MedecinController extends Controller
{
    //
    public function V_home(){
        return Inertia::render('Medecin/Home');
    }
}
