<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
     public function index()
    {
        return view('students.progress.index');
    }
}
