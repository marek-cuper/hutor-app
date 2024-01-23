<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Tag;
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
            if($tag->tag_status === 1){
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
        $request->validate([
            'user_tags' => 'nullable',
        ]);

        $user_tags = $request->input('user_tags');
        $tags = Tag::all();
        if(sizeof($tags) === sizeof($user_tags)){
            //delete all old tags for specific user

            $old_user_tag = User_tag::all()->where('user_id', Auth::user()->id)->first();
            while ($old_user_tag != null){
                $old_user_tag->delete();
                $old_user_tag = User_tag::all()->where('user_id', Auth::user()->id)->first();
            }


            $user_tags_pref = [];
            $user_tags_block = [];

            for ($x = 0; $x < sizeof($tags); $x++) {
                if($user_tags[$x] === '1'){
                    $user_tag = new User_tag([
                        'user_id' => Auth::user()->id,
                        'tag_id' => $tags[$x]->id,
                        'tag_status' => true,

                    ]);
                    $user_tag->save();
                    $user_tags_pref[] = $tags[$x]->id;
                }
                if($user_tags[$x] === '-1'){
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

        return view('/preferencie');
        //$this->pouzivatelPreferencieGet($request);
    }

    public function pouzivatelMapGet(Request $request){

        return view('/map');
    }

    public function pouzivatelMapSet(Request $request){

        return view('/preferencie');
    }

}
