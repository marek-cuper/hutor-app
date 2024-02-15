<?php

namespace App\Http\Controllers;

use App\Models\Poll_option;
use App\Models\Post_image;
use App\Models\User_poll_vote;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function domovGet(Request $request){
        return view('/domov');
    }

    public function domov_zobrazeniePost(Request $request){
        $post_id = $request->input('post_id');
        $posts_all_images = Post_image::where('post_id', $post_id)->orderBy('order')->get();
        $show_posts_images = $posts_all_images->pluck('image_name')->toArray();

        $post_question = Post::where('id', $post_id)->first()->poll_text;
        $post_poll_options_images = null;
        $post_poll_options_text = null;
        if($post_question !== null){
            $post_poll_options = Poll_option::where('post_id', $post_id)->orderBy('order')->get();
            $post_poll_options_images = $post_poll_options->pluck('image_name')->toArray();
            $post_poll_options_text = $post_poll_options->pluck('text')->toArray();
        }

        $post_poll_options_id = Poll_option::where('post_id', $post_id)->get()->pluck('id')->toArray();
        $user_poll_votes_id = User_poll_vote::where('user_id', Auth::user()->id)->get()->pluck('poll_option_id')->toArray();

        $user_poll_option_number = -1;
        foreach ($post_poll_options_id as $option_id) {
            if(in_array($option_id, $user_poll_votes_id)){
                $user_poll_option_number = Poll_option::where('id', $option_id)->first()->order;
                break;
            }
        }

        $poll_option_votes = Poll_option::where('post_id', $post_id)->orderBy('order')->get()->pluck('votes')->toArray();

        return response()->json([
            'show_posts_images' => $show_posts_images,
            'post_poll_options_images' => $post_poll_options_images,
            'post_poll_options_text' => $post_poll_options_text,
            'user_poll_option_number' => $user_poll_option_number,
            'poll_option_votes' => $poll_option_votes,
        ]);

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
            'poll_question' => 'nullable',
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
            'poll_text' => $request->input('poll_question'),
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

        $checkboxPoll = $request->input('poll_question');
        if(isset($checkboxPoll)){
            $checkboxPollImg = $request->input('checkBoxPollImage');
            $pollText = $request->input('poll_text');
            $pollImages = $request->input('poll_images');
            if (!isset($checkboxPollImg)){
                for ($i = 0; $i < sizeof($pollImages); $i++) {
                    $pollImages[$i] = null;
                }
            }

            for ($i = 0; $i < sizeof($pollText); $i++) {
                $polll_option_to_save = new Poll_option([
                    'post_id' => $post->id,
                    'order' => $i,
                    'text' => $pollText[$i],
                    'image_name' => $pollImages[$i],
                    'votes' => 0,
                ]);
                $polll_option_to_save->save();
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
