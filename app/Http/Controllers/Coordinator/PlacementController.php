<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Placement;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PlacementController extends Controller
{
    public function index(Request $request)
    {
        return view('coordinator.placements.index');

}
}