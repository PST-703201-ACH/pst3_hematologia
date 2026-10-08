<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;

class AdmvoController extends Controller
{
    public function dashboards()
    {
        //Citas
        $totalCitas = Cita::count();

        return response()->json([
            //Citas
            'citas' => $totalCitas
        ]);
    }
}
