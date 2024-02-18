<?php

namespace App\Http\Controllers;

use App\Models\Poll_option;
use App\Models\User_poll_vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    public function pridaj_moznost_anketaPost(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);

        // Store the uploaded image
        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->store('images/polls', 'public');

            // Return the image name or any other response
            return response()->json(['imageName' => $imageName], 200);
        }

        // Handle the case if no image is uploaded
        return response()->json(['error' => 'No image uploaded'], 400);

    }

    public function anketa_hlasujPost(Request $request){
        $input_post_id = $request->input('post_id');
        $input_poll_option_number = $request->input('poll_option_number');

        $poll_option = Poll_option::where('post_id', $input_post_id)->where('order', $input_poll_option_number)->first();

        $user_poll_option_vote_to_save = new User_poll_vote([
            'user_id' => Auth::user()->id,
            'poll_option_id' => $poll_option->id,

        ]);
        $user_poll_option_vote_to_save->save();

        $poll_option->votes += 1;
        $poll_option->save();


        $poll_option_votes = Poll_option::where('post_id', $input_post_id)->orderBy('order')->get()->pluck('votes')->toArray();

        return response()->json([
            'poll_option_votes' => $poll_option_votes,
        ]);
    }


}
