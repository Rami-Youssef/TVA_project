<?php

namespace App\Http\Controllers;

use App\Models\Cnss;
use App\Models\Entreprise;
use Illuminate\Http\Request;

class EtatsController extends Controller
{
    public function index()
    {
        // This controller will later filter CNSS declarations specifically for the "List etats" view
        $declarations = Cnss::with('entreprise')->paginate(10);
        return view('etats.index', compact('declarations'));
    }
}