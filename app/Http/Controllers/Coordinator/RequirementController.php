<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;

class RequirementController extends Controller
{
    public function index()
    {
        return view('coordinator.requirements.index');
    }
}