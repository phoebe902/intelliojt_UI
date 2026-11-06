<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
     public function index()
    {
        return view('supervisor.evaluations.index');
    }
}
