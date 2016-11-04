<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\HPMEConstants;


class PlanificacionRegion extends Controller
{    
    public function planificacionRegion(){
        return view('planificacionregion');
    }    
}
