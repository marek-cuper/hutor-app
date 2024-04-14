<?php

namespace App\Http\Controllers;

use App\Models\Poll_option;
use App\Models\Post_comment;
use App\Models\Post_image;
use App\Models\Post_region;
use App\Models\Post_tag;
use App\Models\User;
use App\Models\User_comment_vote;
use App\Models\User_poll_vote;
use App\Models\User_post_vote;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use function Sodium\add;

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

        $post_creator = User::where('id', $post->creator_id)->first();

        $poll_options = [];
        if($post->poll_text !== null){
            $poll_options = Poll_option::where('post_id', $post_id)->orderBy('order')->get();
        }

        $user_poll_votes_id = User_poll_vote::where('user_id', Auth::user()->id)->get()->pluck('poll_option_id')->toArray();

        $user_poll_option_number = -1;
        foreach ($poll_options as $option) {
            if(in_array($option->id, $user_poll_votes_id)){
                $user_poll_option_number = $option->order;
                break;
            }
        }


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

        //Adding comments to showed post, make sure for right order
        $comments = [];
        $comments_main = Post_comment::where('post_id', $post_id)->where('upper_comment_id', null)->orderBy('down_votes')->orderByDesc('up_votes')->get();
        foreach ($comments_main as $comment) {
            $comments[] = $comment;
            $comments_lower = Post_comment::where('upper_comment_id', $comment->id)->orderBy('order')->get();
            foreach ($comments_lower as $comment_lower) {
                $comments[] = $comment_lower;
            }
        }
        $comment_profiles = [];
        $post_comments_user_voted = [];
        foreach ($comments as $comment) {
            $comment_profiles[] = User::where('id',  $comment->user_id)->first();

            $comment_user_vote = User_comment_vote::where('comment_id', $comment->id)->where('user_id', Auth::user()->id)->first();
            if($comment_user_vote){
                if($comment_user_vote->up_vote){
                    $post_comments_user_voted[] = '+';
                }else{
                    $post_comments_user_voted[] = '-';
                }
            }else{
                $post_comments_user_voted[] = '';
            }
        }

        return response()->json([
            'post' => $post,
            'show_post_creator' => $post_creator,
            'show_posts_images' => $show_posts_images,
            'poll_options' => $poll_options,
            'user_poll_option_number' => $user_poll_option_number,
            'post_vote_status' => $post_vote_status,
            'poll_up_votes' => $post_up_votes,
            'poll_down_votes' => $post_down_votes,
            'poll_oppened' => $post_openned,
            'comments' => $comments,
            'comment_profiles' => $comment_profiles,
            'comment_user_voted' => $post_comments_user_voted,
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
            }else{
                if ($input_up_vote == 1){
                    $post->up_votes -= 1;
                }else{
                    $post->down_votes -= 1;
                }
                $post_vote->delete();
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
            'post_up_votes' => $post_up_votes,
            'post_down_votes' => $post_down_votes,
            'post_oppened' => $post_openned,

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

    public function post_pridaj_komentPost(Request $request){

        $order = null;
        $upper_comment_id = null;
        if($request->input('upper_comment_id') != null){
            $com = Post_comment::where('post_id', $request->input('post_id'))
                ->where('upper_comment_id', $request->input('upper_comment_id'))
                ->whereNotNull('order')
                ->orderByDesc('order')
                ->first();
            if($com){
                $order = $com->order + 1;
            }else{
                $order = 0;
            }
            $upper_comment_id = $request->input('upper_comment_id');

        }

        $post_comment = new Post_comment([
            'post_id' => $request->input('post_id'),
            'user_id' => Auth::user()->id,
            'upper_comment_id' => $upper_comment_id,
            'order' => $order,
            'text' => $request->input('comment_text'),
            'up_votes' => 0,
            'down_votes' => 0,
        ]);
        $post_comment->save();

        return response()->json([
            'comment_id' => $post_comment->id,

        ]);
    }

    public function post_hlasuj_komentPost(Request $request){

        $input_comment_id = $request->input('comment_id');
        $input_comment_vote = $request->input('up_vote');

        $post_comment = Post_comment::where('id', $input_comment_id)->first();

        $user_comment_vote = User_comment_vote::where('comment_id', $input_comment_id)->where('user_id', Auth::user()->id)->first();
        if($user_comment_vote){
            if($input_comment_vote != $user_comment_vote->up_vote){
                if ($input_comment_vote == 1){
                    $post_comment->up_votes += 1;
                    $post_comment->down_votes -= 1;
                }else{
                    $post_comment->down_votes += 1;
                    $post_comment->up_votes -= 1;
                }
                $user_comment_vote->up_vote = $input_comment_vote;
                $user_comment_vote->save();
            }else{
                if ($input_comment_vote == 1){
                    $post_comment->up_votes -= 1;
                }else{
                    $post_comment->down_votes -= 1;
                }
                $user_comment_vote->delete();
        }
        }else{
            $vote = new User_comment_vote([
                'comment_id' => $input_comment_id,
                'user_id' => Auth::user()->id,
                'up_vote' => $input_comment_vote,
            ]);
            $vote->save();

            if ($input_comment_vote){
                $post_comment->up_votes += 1;
            }else{
                $post_comment->down_votes += 1;
            }
        }

        $post_comment_up_votes = $post_comment->up_votes;
        $post_comment_down_votes = $post_comment->down_votes;
        $comment_vote_status = '';

        $user_comment_vote = User_comment_vote::where('user_id', Auth::user()->id)->where('comment_id', $input_comment_id)->first();
        if($user_comment_vote){
            if($user_comment_vote->up_vote == 1){
                $comment_vote_status = '+';
            }else{
                $comment_vote_status = '-';
            }
        }
        $post_comment->save();

        return response()->json([
            'comment_vote_result' => $comment_vote_status,
            'comment_up_votes' => $post_comment_up_votes,
            'comment_down_votes' => $post_comment_down_votes,
        ]);
    }

    public function post_vymaz_komentPost(Request $request){

        $input_comment_id = $request->input('comment_id');
        $user_comment = Post_comment::where('id', $input_comment_id)->first();
        if($user_comment->upper_comment_id != null){
            $lower_comments = Post_comment::where('upper_comment_id', $input_comment_id)->get();
            foreach ($lower_comments as $comment) {
                $comment->delete();
            }
        }
        $user_comment->delete();
    }

    public function pridaj_prispevokPost(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
            'poll_question' => 'nullable',
            'tags' => 'nullable',
        ]);

        //Controll if poll have at least 2 options
        $pollText = $request->input('poll_text');
        $poll_question = null;
        if(sizeof($pollText) > 1){
            $poll_question = $request->input('poll_question');
        }

        $post = new Post([
            'up_votes' => 0,
            'down_votes' => 0,
            'watched' => 0,
            'openned' => 0,
            'creator_id' => Auth::user()->id,
            'title' => $request->input('title'),
            'text' => $request->input('text'),
            'poll_text' => $poll_question,
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
        //Controll if checkbox is on, poll have at least 2 options and poll question is set
        if(isset($checkboxPoll) && sizeof($pollText) > 1 && $poll_question != null){
            $checkboxPollImg = $request->input('checkBoxPollImage');
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

    function add_to_big_container($request, $unsortedArray, &$big_container_posts_id_sorted, &$big_container_posts_credit_sorted) {
        $user_tags_block = $request->session()->get('user_tags_block');
        foreach ($unsortedArray as $post) {
            $found = false;
            foreach ($user_tags_block as $tag_id){
                $post_tags = Post_tag::where('post_id', $post->id)->pluck('tag_id')->toArray();
                if (in_array($tag_id, $post_tags)) {
                    $found = true;
                }
            }
            if (!$found){
                $credit = $this->post_credit($request, $post);
                $index = $this->index_to_insert($big_container_posts_credit_sorted, $credit);
                array_splice($big_container_posts_credit_sorted, $index, 0, $credit);
                array_splice($big_container_posts_id_sorted, $index, 0, $post->id);
            }
        }

    }

    function index_to_insert($sortedArray, $number) {
        $left = 0;
        $right = count($sortedArray) - 1;

        // Handle the case when the array is empty
        if (count($sortedArray) === 0) {
            return 0;
        }

        while ($left <= $right) {
            $mid = $left + floor(($right - $left) / 2);

            if ($sortedArray[$mid] < $number) {
                $right = $mid - 1;
            } else {
                $left = $mid + 1;
            }
        }

        return $left;
    }

    private function post_credit($request, mixed $post) {
        $credit = 0;

        //adding <-5, 8> credits depend on watch and open post ratio
        if($post->openned != 0 || $post->watched != 0){
            $woKoef = $post->openned/$post->watched;
            $woCredits = 4 - ($woKoef * 8);
            $credit -= $woCredits;
        }

        //adding <-10, 10> credits depend on up and down votes ratio
        if($post->up_votes != 0 || $post->down_votes != 0){
            $votesKoef = ($post->up_votes - $post->down_votes) / ($post->up_votes + $post->down_votes);
            $votesCredits = $votesKoef * 10;
            $credit += $votesCredits;
        }

        //adding <0, 24> credits depend on tags preferably
        $tagCredits = 0;
        $user_tags_pref = $request->session()->get('user_tags_pref');
        $post_tags = Post_tag::where('post_id', $post->id)->pluck('tag_id')->toArray();
        foreach ($user_tags_pref as $tag_id){
            if (in_array($tag_id, $post_tags)) {
                $tagCredits += 3;
            }
        }
        $credit += $tagCredits;

        //adding <0, 12> credits depend on regions preferably
        $regCredits = 0;
        $user_regs = $request->session()->get('user_regions');
        $post_regs = Post_region::where('post_id', $post->id)->pluck('region_id')->toArray();
        foreach ($user_regs as $reg_id){
            if (in_array($reg_id, $post_regs)) {
                $regCredits += 3;
            }
        }
        $credit += $regCredits;

        return $credit;
    }

    public function vymaz_prispevokPost(Request $request){
        Post::where('id', $request->input('post_id'))->delete();
        $this->vymaz_nacitane($request);
        $this->nacitaj_prispevkyPost($request);
    }

    public function vymaz_nacitane(Request $request){
        $request->session()->forget('posts');
        $request->session()->forget('posts_images');
        $request->session()->forget('posts_tags');
        $request->session()->forget('posts_regions');
        $request->session()->forget('post_loaded_lowest_time');
        $request->session()->forget('post_loaded_highest_time');
        $request->session()->forget('big_container_posts_id_sorted');
        $request->session()->forget('big_container_posts_credit_sorted');
    }

    public function nacitaj_prispevkyPost(Request $request) {

        $big_container_posts_id_sorted = [];
        $big_container_posts_credit_sorted = [];
        if($request->session()->has(['big_container_posts_id_sorted', 'big_container_posts_credit_sorted'])){
            $big_container_posts_id_sorted = $request->session()->get('big_container_posts_id_sorted');
            $big_container_posts_credit_sorted = $request->session()->get('big_container_posts_credit_sorted');
        }

        if(!$request->session()->has(['post_loaded_lowest_time', 'post_loaded_highest_time'])){
            $big_container_posts_unsorted = Post::orderByDesc('created_at')->limit(100)->get();
            $request->session()->put('post_loaded_highest_time', $big_container_posts_unsorted->first()->created_at);
            $request->session()->put('post_loaded_lowest_time', $big_container_posts_unsorted->last()->created_at);
            $this->add_to_big_container($request, $big_container_posts_unsorted, $big_container_posts_id_sorted, $big_container_posts_credit_sorted);
        }else{
            $post_to_load = [];
            $post_loaded_highest_time = $request->session()->get('post_loaded_highest_time');
            $post_loaded_lowest_time = $request->session()->get('post_loaded_lowest_time');

            $post_to_load_higher_part = Post::where('created_at', '>', $post_loaded_highest_time)->orderBy('created_at')->limit(5)->get();
            $post_to_load_lower_part = Post::where('created_at', '<', $post_loaded_lowest_time)->orderByDesc('created_at')->limit(15)->get();

            if(!$post_to_load_higher_part->isEmpty() && !$post_to_load_lower_part->isEmpty()){
                $request->session()->put('post_loaded_highest_time', $post_to_load_higher_part->last()->created_at);
                $request->session()->put('post_loaded_lowest_time', $post_to_load_lower_part->last()->created_at);
                $post_to_load = $post_to_load_higher_part->merge($post_to_load_lower_part);
            }elseif (!$post_to_load_higher_part->isEmpty()){
                $request->session()->put('post_loaded_highest_time', $post_to_load_higher_part->last()->created_at);
                $post_to_load = $post_to_load_higher_part;
            }elseif (!$post_to_load_lower_part->isEmpty()){
                $request->session()->put('post_loaded_lowest_time', $post_to_load_lower_part->last()->created_at);
                $post_to_load = $post_to_load_lower_part;
            }
            $this->add_to_big_container($request, $post_to_load, $big_container_posts_id_sorted, $big_container_posts_credit_sorted);
        }

        $posts = [];


        $num_of_posts = sizeof($big_container_posts_id_sorted);
        if($num_of_posts > 15){
            $num_of_posts = 15;
        }
        if($request->session()->has('posts')){
            $posts = $request->session()->get('posts');
        }
        for ($i = 0; $i < $num_of_posts; $i++) {
            $post = Post::where('id', $big_container_posts_id_sorted[$i])->first();
            $post->watched += 1;
            $post->save();
            $posts[] = $post;
        }

        array_splice($big_container_posts_id_sorted, 0, $num_of_posts);
        array_splice($big_container_posts_credit_sorted, 0, $num_of_posts);

        $request->session()->put('big_container_posts_id_sorted', $big_container_posts_id_sorted);
        $request->session()->put('big_container_posts_credit_sorted', $big_container_posts_credit_sorted);


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


        $request->session()->put('posts', $posts);
        $request->session()->put('posts_images', $posts_images);
        $request->session()->put('posts_tags', $posts_tags);
        $request->session()->put('posts_regions', $posts_regions);

        return response()->json([
            'posts' => $posts,
            'posts_images' => $posts_images,
            'posts_tags' => $posts_tags,
            'posts_regions' => $posts_regions,
        ]);
    }


}
