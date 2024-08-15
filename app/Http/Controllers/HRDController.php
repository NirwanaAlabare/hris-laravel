<?php

namespace App\Http\Controllers\Hris;

use Illuminate\Http\Request;

class HRDController extends Controller
{
    public function index(){
        return View::make('hris/hrd', $this->data);
    }
}
