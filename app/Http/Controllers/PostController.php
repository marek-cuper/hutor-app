<?php

namespace App\Http\Controllers;

use App\Models\Poll_option;
use App\Models\Post_image;
use App\Models\User_poll_vote;
use App\Models\User_post_vote;
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

        $post = Post::where('id', $post_id)->first();
        $post->openned += 1;
        $post->save();

        $post_question = $post->poll_text;
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

        $post_vote_status = "";
        $post_vote = User_post_vote::where('user_id', Auth::user()->id)->where('post_id', $post_id)->first();
        if($post_vote){
            if($post_vote->up_vote){
                $post_vote_status = '+';
            }else{
                $post_vote_status = '-';
            }
        }

        $post_up_votes = $post->up_votes;
        $post_down_votes = $post->down_votes;
        $post_openned = $post->openned;


        return response()->json([
            'show_posts_images' => $show_posts_images,
            'post_poll_options_images' => $post_poll_options_images,
            'post_poll_options_text' => $post_poll_options_text,
            'user_poll_option_number' => $user_poll_option_number,
            'poll_option_votes' => $poll_option_votes,
            'post_vote_status' => $post_vote_status,
            'poll_up_votes' => $post_up_votes,
            'poll_down_votes' => $post_down_votes,
            'poll_oppened' => $post_openned,
        ]);

    }

    public function post_hlasujPost(Request $request){
        $input_post_id = $request->input('post_id');
        $input_up_vote = $request->input('up_vote');

        $post = Post::where('id', $input_post_id)->first();

        $post_vote = User_post_vote::where('user_id', Auth::user()->id)->where('post_id', $input_post_id)->first();
        if($post_vote){
            if($post_vote->up_vote != $input_up_vote){
                if ($input_up_vote == 1){
                    $post->up_votes += 1;
                    $post->down_votes -= 1;
                }else{
                    $post->down_votes += 1;
                    $post->up_votes -= 1;
                }
                $post_vote->up_vote = $input_up_vote;
                $post_vote->save();
            }
        }else{
            $user_post_vote_to_save = new User_post_vote([
                'user_id' => Auth::user()->id,
                'post_id' => $input_post_id,
                'up_vote' => $input_up_vote,
            ]);
            $user_post_vote_to_save->save();

            if ($input_up_vote){
                $post->up_votes += 1;
            }else{
                $post->down_votes += 1;
            }
        }


        $post_up_votes = $post->up_votes;
        $post_down_votes = $post->down_votes;
        $post_openned = $post->openned;
        $post_vote_status = '';

        $post_vote = User_post_vote::where('user_id', Auth::user()->id)->where('post_id', $input_post_id)->first();
        if($post_vote){
            if($post_vote->up_vote == 1){
                $post_vote_status = '+';
            }else{
                $post_vote_status = '-';
            }
        }
        $post->save();

        return response()->json([
            'post_vote_status' => $post_vote_status,
            'poll_up_votes' => $post_up_votes,
            'poll_down_votes' => $post_down_votes,
            'poll_oppened' => $post_openned,

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
