<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Conversation_message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function spravyGet(Request $request){

        $conversations = Conversation::where('user1_id', Auth::user()->id)->orWhere('user2_id', Auth::user()->id)->orderByDesc('last_message_sent_at')->get();
        $messages = [];
        $users = [];
        foreach ($conversations as $conv){
            $messages[] = Conversation_message::where('conversation_id', $conv->id)->orderByDesc('created_at')->first();
            $user = User::where('id', $conv->user1_id)->first();
            if(Auth::user()->id == $user->id){
                $user = User::where('id', $conv->user2_id)->first();
            }
            $users[] = $user;
        }
        $request->session()->put('conversations', $conversations);
        $request->session()->put('conversations_first_message', $messages);
        $request->session()->put('conversations_user', $users);
        return view('spravy');
    }

    public function vrat_konverzaciePost(){
        $conversations = Conversation::where('user1_id', Auth::user()->id)->orWhere('user2_id', Auth::user()->id)->orderByDesc('last_message_sent_at')->get();
        $messages = [];
        $users = [];
        foreach ($conversations as $conv){
            $messages[] = Conversation_message::where('conversation_id', $conv->id)->orderByDesc('created_at')->first();
            $user = User::where('id', $conv->user1_id)->first();
            if(Auth::user()->id == $user->id){
                $user = User::where('id', $conv->user2_id)->first();
            }
            $users[] = $user;
        }

        return response()->json([
            'conversations' => $conversations,
            'conversations_first_message' => $messages,
            'conversations_user' => $users
        ]);
    }

    public function spravyKonverzacieGet(Request $request){
        $request->session()->put('conversation_show', false);
        return redirect()->route('spravy');
    }

    public function showConversationFromUserPost(Request $request){
        $user_id = $request->input('user_id');
        $messages = null;
        $another_user = User::where('id', $user_id)->first();
        $conversation = Conversation::where('user1_id', $user_id)->where('user2_id', Auth::user()->id)->first();
        if ($conversation === null){
            $conversation = Conversation::where('user1_id', Auth::user()->id)->where('user2_id', $user_id)->first();
        }
        if ($conversation !== null){
            $messages = Conversation_message::where('conversation_id', $conversation->id)->orderBy('created_at')->get();

        }

        $request->session()->put('conversation_show', true);
        $request->session()->put('conversation_messages', $messages);
        $request->session()->put('another_user', $another_user);

        return redirect()->route('spravy');
    }

    public function showConversationFromIdPost(Request $request){
        $messages = Conversation_message::where('conversation_id', $request->input('conv_id'))->orderBy('created_at')->get();
        $conversation = Conversation::where('id', $request->input('conv_id'))->first();
        if($conversation->user1_id == Auth::user()->id){
            $another_user = User::where('id', $conversation->user2_id)->first();
            $conversation->user1_openned = true;
        }else{
            $another_user = User::where('id', $conversation->user1_id)->first();
            $conversation->user2_openned = true;
        }
        $conversation->save();

        return response()->json([
            'conversation_messages' => $messages,
            'another_user' => $another_user
        ]);
    }

    public function posli_spravuPost(Request $request){

        $conversation = Conversation::where('user1_id', $request->input('sender_id'))->where('user2_id', $request->input('receiver_id'))->first();
        if($conversation === null){
            $conversation = Conversation::where('user1_id', $request->input('receiver_id'))->where('user2_id', $request->input('sender_id'))->first();
            if($conversation === null){
                $conversation = new Conversation([
                    'user1_id' => $request->input('sender_id'),
                    'user2_id' => $request->input('receiver_id'),
                    'user1_openned' => true,
                    'user2_openned' => false,
                    'last_message_sent_at' => now()
                ]);
                $conversation->save();
            }else{
                $conversation->last_message_sent_at = now();
                $conversation->user1_openned = false;
                $conversation->user2_openned = true;
                $conversation->save();
            }
        }else{
            $conversation->last_message_sent_at = now();
            $conversation->user1_openned = true;
            $conversation->user2_openned = false;
            $conversation->save();
        }


        $message = new Conversation_message([
            'conversation_id' => $conversation->id,
            'sender_id' => $request->input('sender_id'),
            'text' => $request->input('message_text'),
        ]);
        $message->save();

        $messages = Conversation_message::where('conversation_id', $conversation->id)->orderBy('created_at')->get();
        $request->session()->put('conversation_messages', $messages);
    }
}
