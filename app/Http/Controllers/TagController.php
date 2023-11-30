<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagController extends Controller
{
    public function pouzivatelPreferencieGet(Request $request){

        return view('/preferencie');
    }
}
