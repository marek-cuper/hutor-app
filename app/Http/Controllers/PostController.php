<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;

class PostController extends Controller
{
    public function domovGet(Request $request){
        //$request->session()->get('posts', []);
        return view('/domov');

        #$posts = $request->session()->get('posts', []);
        #$postNum = $request->session()->get('postNum', 1);

        #return view('/domov_prispevky')->with('posts', $posts)->with('postNum', $postNum);
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
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
            'poll_text' => 'nullable',
            'tags' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/posts', 'public');
        } else {
            $imagePath = null;
        }

        $post = new Post([
            'title' => $request->input('title'),
            'text' => $request->input('text'),
            'image_name' => $imagePath,
            'poll_text' => $request->input('poll_text'),
        ]);

        $post->save();

        $tags = $request->input('tags');
        $post->tags()->attach($tags);

        return redirect('/domov')->with('success', 'Post created successfully');
    }

    public function vrat_prispevky() {
        $posts = Post::all();

        return $posts;
    }
}
