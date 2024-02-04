<?php

namespace App\Http\Controllers;

use App\Models\Post_image;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function domovGet(Request $request){
        //$request->session()->get('posts', []);
        return view('/domov');

        #$posts = $request->session()->get('posts', []);
        #$postNum = $request->session()->get('postNum', 1);

        #return view('/domov_prispevky')->with('posts', $posts)->with('postNum', $postNum);
    }

    public function domov_zobrazeniePost(Request $request){
        $post_id = $request->input('post_id');
        $posts_all_images = (Post_image::all()->where('post_id', $post_id));
        $show_posts_images = [];
        foreach ($posts_all_images as $image) {
            $show_posts_images[sizeof($show_posts_images)] = $image->image_name;
        }
        //$request->session()->put('show_posts_images', $show_posts_images);

        return response()->json(['success' => true, 'show_posts_images' => $show_posts_images]);
    }

    public function domov_prispevok_dalsiGet(Request $request){
        $posts = $request->session()->get('posts', []);
        $postNum = $request->session()->get('postNum', 1);
         if($posts->count() > ($postNum + 1)){
             $postNum++;
             $request->session()->put('postNum', $postNum);
         }

        return view('/domov_prispevky')->with('posts', $posts)->with('postNum', $postNum);
    }

    public function domov_prispevok_predosliGet(Request $request){
        $posts = $request->session()->get('posts', []);
        $postNum = $request->session()->get('postNum', 1);
        if(0 < $postNum){
            $postNum--;
            $request->session()->put('postNum', $postNum);
        }

        return view('/domov_prispevky')->with('posts', $posts)->with('postNum', $postNum);
    }

    public function domov_prispevokGet($id_post){
        $post = Post::find($id_post);
        return view('domov_prispevky')->with('post', $post);
    }

    public function pridaj_prispevokGet(){
        $tags = Tag::all();
        return view('pridaj_prispevok')->with('tags', $tags);
    }

    public function pridaj_prispevokPost(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
            'poll_text' => 'nullable',
            'tags' => 'nullable',
        ]);


        $post = new Post([
            'up_votes' => 0,
            'down_votes' => 0,
            'watched' => 0,
            'openned' => 0,
            'creator_id' => Auth::user()->id,
            'title' => $request->input('title'),
            'text' => $request->input('text'),
            'poll_text' => $request->input('poll_text'),
        ]);

        $post->save();

        if ($request->hasFile('images.*')) {

            $order = 0;
            //$imagePath = $request->file('image')->store('images/posts', 'public');
            foreach ($request->file('images') as $image_input) {
                // Save or handle each image as needed
                $imageName = $image_input->store('images/posts', 'public');
                $image_to_save = new Post_image([
                    'post_id' => $post->id,
                    'order' => $order,
                    'image_name' => $imageName,
                ]);
                $image_to_save->save();
                $order++;
            }
        }

        $tags = $request->input('tags');
        $post->tags()->attach($tags);

        if($request->input('regions') != null){
            $regions = $request->input('regions');
            $post->regions()->attach($regions);
        }

        return redirect('/domov')->with('success', 'Post created successfully');
    }

    public function vrat_prispevky() {
        $posts = Post::all();

        return $posts;
    }
}
