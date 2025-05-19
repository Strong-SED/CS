<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class RendezVousController extends Controller
{
    //
    public function Rdv_V(){
        return Inertia::render('Secretaire/RDV');
    }
}
