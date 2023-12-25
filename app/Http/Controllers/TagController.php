<?php

namespace App\Http\Controllers;

use App\Models\User_tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Sodium\add;

class TagController extends Controller
{
    public function pouzivatelPreferencieGet(Request $request){
        $user_tags_pref = [];
        $user_tags_block = [];

        $user_tags = (User_tag::all()->where('user_id', Auth::user()->id));
        foreach ($user_tags as $tag) {
            if($tag->tag_status === true){
                $user_tags_pref[] = $tag->tag_id;
            } else{
                $user_tags_block[] = $tag->tag_id;
            }
        }
        $request->session()->put('user_tags_pref', $user_tags_pref);
        $request->session()->put('user_tags_block', $user_tags_block);


        return view('/preferencie');
    }

    public function pouzivatelPreferencieSet(Request $request){
        $user_tags_pref = [];
        $user_tags_block = [];

        $user_tags = (User_tag::all()->where('user_id', Auth::user()->id));
        foreach ($user_tags as $tag) {
            if($tag->tag_status === true){
                $user_tags_pref[] = $tag->tag_id;
            } else{
                $user_tags_block[] = $tag->tag_id;
            }
        }
        $request->session()->put('user_tags_pref', $user_tags_pref);
        $request->session()->put('user_tags_block', $user_tags_block);


        return view('/preferencie');
    }
}
