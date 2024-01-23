<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Post_tag;
use App\Models\Region;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\PostController;
use function Sodium\add;

class AuthController extends Controller
{
    public function prihlasenie(){
        return view('prihlasenie');
    }

    public function prihlaseniePost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $posts = app('App\Http\Controllers\PostController')->vrat_prispevky();
            $posts_tags = [];
            $tags = Tag::all();
            $regions = Region::all();

            foreach ($posts as $post) {
                $post_id = $post->id;
                $tags_on_post = (Post_tag::all()->where('post_id', $post_id));

                $tags_in_array = [];
                foreach ($tags_on_post as $tag) {
                    $tags_in_array[] = $tag->tag_id;
                }
                $posts_tags[sizeof($posts_tags)] = $tags_in_array;
            }

            $request->session()->put('posts', $posts);
            $request->session()->put('posts_tags', $posts_tags);
            $request->session()->put('tags', $tags);
            $request->session()->put('regions', $regions);
            return redirect()->route('domov');
            #app('App\Http\Controllers\PostController')->domov_prispevokGet($posts->first()->id);
            #return view('/domov_prispevky')->with('posts', $posts)->with('postNum', $postNum);
        }

        return redirect(route('prihlasenie'))->with('error', 'Invalid Credentials!');
    }


    public function registracia(){
        return view('registracia');
    }

    public function registraciaPost(Request $request){

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);

        if(!$user){
            return redirect(route(('registracia')))->with(['error', 'Invalid Credentials!']);
        }
        return redirect(route(('prihlasenie')))->with(['success', 'Uspesna registracia']);
    }

    function odhlasenie(Request $request){
        $request->session()->flush();
        Auth::logout();
        return redirect(route(('prihlasenie')));
    }

}
