<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Post_image;
use App\Models\Post_region;
use App\Models\Post_tag;
use App\Models\Region;
use App\Models\Tag;
use App\Models\User;
use App\Models\User_region;
use App\Models\User_tag;
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
            $tags = Tag::all();
            $regions = Region::all();

            $request->session()->put('posts', $posts);
            $request->session()->put('tags', $tags);
            $request->session()->put('regions', $regions);

            $posts_images = [];
            $posts_tags = [];
            $posts_regions = [];
            foreach ($posts as $post) {
                $post_id = $post->id;

                //Check if post have image
                $post_img =(Post_image::all()->where('post_id', $post_id))->where('order', 0)->first();
                if($post_img !== null){
                    $posts_images[] = $post_img->image_name;
                }else{
                    $posts_images[] = null;
                }
                $tags_on_post = (Post_tag::all()->where('post_id', $post_id));
                $regions_on_post = (Post_region::all()->where('post_id', $post_id));

                $tags_in_array = [];
                foreach ($tags_on_post as $tag) {
                    $tags_in_array[] = $tag->tag_id;
                }
                $posts_tags[sizeof($posts_tags)] = $tags_in_array;

                $regions_in_array = [];
                foreach ($regions_on_post as $region) {
                    $regions_in_array[] = $region->region_id;
                }
                $posts_regions[sizeof($posts_regions)] = $regions_in_array;
            }
            $request->session()->put('posts_images', $posts_images);
            $request->session()->put('posts_tags', $posts_tags);
            $request->session()->put('posts_regions', $posts_regions);


            $request->session()->put('user_name', Auth::user()->name);
            $request->session()->put('user_profile_id', Auth::user()->id);
            $request->session()->put('user_profile_image', Auth::user()->image_name);

            //Setting tags chosen be user to session
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

            //Setting regions chosen be user to session
            $user_regions = [];
            $user_regions_dbs = (User_region::all()->where('user_id', Auth::user()->id));
            foreach ($user_regions_dbs as $reg) {
                $user_regions[] = $reg->region_id;
            }
            $request->session()->put('user_regions', $user_regions);




            return redirect()->route('domov');
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

    function profil($id){
        $user = User::findOrFail($id);
        return view('profil', compact('user'));
    }

    function profil_uprava(Request $request){
        return view('profil_uprava');
    }

    function pridaj_obrazokPost(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);

        // Store the uploaded image
        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->store('images/profiles', 'public');

            // Return the image name or any other response
            return response()->json(['imageName' => $imageName], 200);
        }

        // Handle the case if no image is uploaded
        return response()->json(['error' => 'No image uploaded'], 400);

    }

    function uloz_obrazokPost(Request $request){
        $request->validate([
            'profile_image_name' => 'required|string' // Adjust the validation rules as needed
        ]);

        $user = Auth::user();
        $user->image_name = $request->input('profile_image_name');
        $user->save();

        $request->session()->put('user_profile_image', $user->image_name);

        return redirect(route(('profil_uprava')));
    }

}
