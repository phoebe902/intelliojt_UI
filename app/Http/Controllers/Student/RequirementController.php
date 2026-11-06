<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
     public function index()
    {
        return view('students.requirements.index');
    }
}
