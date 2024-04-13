<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Tag;
use App\Models\User_region;
use App\Models\User_tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Sodium\add;

class TagController extends Controller
{
    public function pouzivatelPreferencieGet(Request $request){
        return view('/preferencie');
    }

    public function pouzivatelPreferencieSet(Request $request){
        $request->validate([
            'user_tags' => 'nullable',
        ]);

        $user_tags_input = $request->input('user_tags');
        $tags = Tag::all();
        if(sizeof($tags) === sizeof($user_tags_input)){
            //delete all old tags for specific user

            $old_user_tag = User_tag::all()->where('user_id', Auth::user()->id)->first();
            while ($old_user_tag != null){
                $old_user_tag->delete();
                $old_user_tag = User_tag::all()->where('user_id', Auth::user()->id)->first();
            }


            $user_tags_pref = [];
            $user_tags_block = [];

            for ($x = 0; $x < sizeof($tags); $x++) {
                if($user_tags_input[$x] === '1'){
                    $user_tag = new User_tag([
                        'user_id' => Auth::user()->id,
                        'tag_id' => $tags[$x]->id,
                        'tag_status' => true,
                    ]);
                    $user_tag->save();
                    $user_tags_pref[] = $tags[$x]->id;
                }
                if($user_tags_input[$x] === '-1'){
                    $user_tag = new User_tag([
                        'user_id' => Auth::user()->id,
                        'tag_id' => $tags[$x]->id,
                        'tag_status' => false,
                    ]);
                    $user_tag->save();
                    $user_tags_block[] = $tags[$x]->id;
                }
            }

        }

        $request->session()->put('user_tags_pref', $user_tags_pref);
        $request->session()->put('user_tags_block', $user_tags_block);


        app('App\Http\Controllers\PostController')->vymaz_nacitane($request);
        app('App\Http\Controllers\PostController')->nacitaj_prispevkyPost($request);

        return view('/preferencie');
    }

    public function pouzivatelRegionySet(Request $request){
        $request->validate([
            'user_regions' => 'nullable',
        ]);

        $user_regions_input = $request->input('user_regions');

        $old_user_reg = User_region::all()->where('user_id', Auth::user()->id)->first();
        while ($old_user_reg != null){
            $old_user_reg->delete();
            $old_user_reg = User_region::all()->where('user_id', Auth::user()->id)->first();
        }

        $user_regions = [];

        if($user_regions_input !== null){
            for ($x = 0; $x < sizeof($user_regions_input); $x++) {
                $user_reg = new User_region([
                    'user_id' => Auth::user()->id,
                    'region_id' => $user_regions_input[$x],
                ]);
                $user_reg->save();
                $user_regions[] = $user_regions_input[$x];
            }
        }

        $request->session()->put('user_regions', $user_regions);

        app('App\Http\Controllers\PostController')->vymaz_nacitane($request);
        app('App\Http\Controllers\PostController')->nacitaj_prispevkyPost($request);

        return view('/preferencie');
    }

}
