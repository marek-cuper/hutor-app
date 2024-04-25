<?php

namespace App\Http\Controllers;

use App\Models\Conversation_message;
use App\Models\Post;
use App\Models\Post_comment;
use App\Models\Region;
use App\Models\Tag;
use App\Models\User;
use App\Models\User_comment_vote;
use App\Models\User_moderator;
use App\Models\User_post_vote;
use App\Models\User_region;
use App\Models\User_tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function prihlasenie(){
        return view('prihlasenie');
    }

    public function prihlaseniePost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|alpha_num',
        ]);
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->put('user', Auth::user());
            $mod = User_moderator::where('user_id', Auth::user()->id)->first();
            $privileges = 0;
            if($mod != null){
                if($mod->admin){
                    $privileges = 2;
                }else{
                    $privileges = 1;
                }
            }
            $request->session()->put('privileges', $privileges);
            $this->moderatoriSet($request);

            $tags = Tag::all();
            $regions = Region::all();

            $request->session()->put('tags', $tags);
            $request->session()->put('regions', $regions);

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

            app('App\Http\Controllers\PostController')->nacitaj_prispevkyPost($request);

            return redirect()->route('domov');
        }


        return redirect()->back()->with('error', 'Chyba. Nespravne prihlasovacie udaje.');
    }


    public function registracia(){
        return view('registracia');
    }

    public function registraciaPost(Request $request){
        $rules = [
            'name' => 'required|string|min:5|alpha_num|unique:users',
            'email' => 'required|email|unique:users',
            'password1' => 'required|string|min:8|alpha_num',
            'password2' => 'same:password1',
        ];

        $messages = [
            'name' => 'Meno nie je unikátne alebo nespĺnňa požiadavky aspoň 5 znakov pozostavajucich z čisiel a pismen.',
            'email' => 'Email nie je unikátny alebo má neplatny format.',
            'password1' => 'Heslo nesplna poziadavky aspon 8 znakov pozostavajucich z cisiel a pismen.',
            'password2' => 'Opakovane heslo sa nezhoduje s heslom.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $error =  $validator->errors()->first();
            return redirect()->back()->with('error', $error)->withInput();
        }


        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password1);
        $user = User::create($data);

        if(!$user){
            return redirect(route(('registracia')))->with(['error', 'Invalid Credentials!']);
        }
        return redirect(route(('prihlasenie')))->with('success', 'Uspesna registracia');
    }

    function odhlasenie(Request $request){
        $request->session()->flush();
        Auth::logout();
        return redirect(route(('prihlasenie')));
    }

    function profilGet($id){
        $user = User::findOrFail($id);

        $numberOfPosts = Post::where('creator_id', $id)->count();
        $numberOfComments = Post_comment::where('user_id', $id)->count();
        $numberOfPostsUpVotes = User_post_vote::where('user_id', $id)->where('up_vote', true)->count();
        $numberOfPostsDownVotes = User_post_vote::where('user_id', $id)->where('up_vote', false)->count();
        $numberOfCommentsUpVotes = User_comment_vote::where('user_id', $id)->where('up_vote', true)->count();
        $numberOfCommentsDownVotes = User_comment_vote::where('user_id', $id)->where('up_vote', false)->count();
        $numberOfMessages = Conversation_message::where('sender_id', $id)->count();

        $data = [
            'numberOfPosts' => $numberOfPosts,
            'numberOfComments' => $numberOfComments,
            'numberOfPostsUpVotes' => $numberOfPostsUpVotes,
            'numberOfPostsDownVotes' => $numberOfPostsDownVotes,
            'numberOfCommentsUpVotes' => $numberOfCommentsUpVotes,
            'numberOfCommentsDownVotes' => $numberOfCommentsDownVotes,
            'numberOfMessages' => $numberOfMessages,
            'another_user' => $user
        ];
        return view('profil', compact('data'));
    }

    function profil_upravaGet(Request $request){
        return view('profil_uprava');
    }

    function pridaj_obrazokPost(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);

        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->store('images/profiles', 'public');
            return response()->json(['imageName' => $imageName], 200);
        }

        return response()->json(['error' => 'No image uploaded'], 400);

    }

    function uloz_obrazokPost(Request $request){
        $request->validate([
            'profile_image_name' => 'required|string'
        ]);

        $user = Auth::user();
        $user->image_name = $request->input('profile_image_name');
        $user->save();

        $request->session()->put('user', Auth::user());

        return redirect(route(('profil_uprava')));
    }

    function overenie_menoPost(Request $request){
        $unique = false;
        $text = '';

        $rules = [
            'name' => ['required', 'string', 'min:5', 'alpha_num'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $text = 'Meno neobsahuje len pismena a cisla alebo neobsahuje aspon 5 znakov';
        }else{
            $exists = User::all()->contains('name', $request->input('name'));
            if($exists){
                $text = 'Dane meno je uz pouzivane.';
            }else{
                $unique = true;
            }
        }

        return response()->json([
            'unique' => $unique,
            'text' => $text
        ]);
    }

    function overenie_emailPost(Request $request){
        $unique = false;
        $text = '';

        $rules = [
            'email' => ['required', 'email'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $text = 'Zadany email nema format emailu.';
        }else{
            $exists = User::all()->contains('email', $request->input('email'));
            if($exists){
                $text = 'Dany email je uz pouzivany.';
            }else{
                $unique = true;
            }
        }

        return response()->json([
            'unique' => $unique,
            'text' => $text
        ]);
    }

    function nastevenie_udajovPost(Request $request){


        $relogin = false;

        $user = Auth::user();

        if(Hash::check($request->old_password, $user->password)){
            if($request->name != ''){

                try {
                    $request->validate([
                        'name' => 'required|min:5|regex:/^[a-zA-Z0-9]+$/',
                    ]);
                } catch (ValidationException $e) {
                    return redirect()->back()->with('error', 'Chyba. Meno nesplna poziadavky.');
                }

                $exists = User::all()->contains('name', $request->name);
                if(!$exists){
                    $user->name = $request->name;
                }
            }
            if($request->email != ''){

                try {
                    $request->validate([
                        'email' => 'required|email',
                    ]);
                } catch (ValidationException $e) {
                    return redirect()->back()->with('error', 'Chyba. Email nesplna poziadavky.');
                }

                $exists = User::all()->contains('email', $request->email);
                if(!$exists){
                    $user->email = $request->email;
                    $relogin = true;
                }
            }
            if($request->new_password1 != ''){

                try {
                    $request->validate([
                        'new_password1' => 'required|min:8|regex:/^[a-zA-Z0-9]+$/', // Validation for the first password
                        'new_password2' => 'required|same:new_password1',
                    ]);
                } catch (ValidationException $e) {
                    return redirect()->back()->with('error', 'Chyba. hesla nesplnaju poziadavky.');
                }
                $user->password = Hash::make($request->new_password1);
                $relogin = true;
            }
        }else{
            return redirect()->back()->with('error', 'Chyba. Stare heslo je nespravne.');
        }
        $user->save();


        if ($relogin){
            $request->session()->flush();
            Auth::logout();
            return redirect(route(('domov')));
        }else{
            $request->session()->put('user', Auth::user());
            return redirect()->back()->with('success', 'Meno bolo uspesne zmenene.');
        }
    }

    public function moderator_panel(Request $request){
        return view('moderator_panel');
    }

    public function moderatoriSet(Request $request){
        $mods = User_moderator::all();
        $users = [];
        foreach ($mods as $mod) {
            $users[] = User::where('id', $mod->user_id)->first();
        }

        $request->session()->put('mods', $mods);
        $request->session()->put('mods_user', $users);
    }

    public function pridaj_moderatoraPost(Request $request){
        $mod = new User_moderator([
            'user_id' => $request->input('user_id'),
            'admin' => false
        ]);
        $mod->save();
        $this->moderatoriSet($request);
    }

    public function odober_moderatoraPost(Request $request){
        User_moderator::where('user_id', $request->input('user_id'))->where('admin', false)->delete();
        $this->moderatoriSet($request);
    }

    public function vymaz_pouzivatelaPost(Request $request){
        $selfDelete = false;
        if ($request->input('user_id') == Auth::user()->id){
            $selfDelete = true;
        }
        User::where('id', $request->input('user_id'))->delete();

        if($selfDelete){
            $this->odhlasenie($request);
        }
    }

    public function vyhladaj_profil(Request $request){
        return view('vyhladavanie_profilu');
    }

    public function vyhladaj_profilPost(Request $request){

        $find_users = User::where('name', 'like', '%' .  $request->input('input') . '%')->get();
        $find = false;
        if($find_users->first() != null){
            $find = true;
        }
        $request->session()->put('find_users', $find_users);
        $request->session()->put('find_text', $request->input('input'));
        $request->session()->put('find', $find);

    }

    public function pravidla(Request $request){
        return view('pravidla');
    }

}
