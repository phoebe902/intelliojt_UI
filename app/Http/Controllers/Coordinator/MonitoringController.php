<?php

namespace App\Http\Controllers\Coordinator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
{
    return view('coordinator.monitoring.index');
}
}
