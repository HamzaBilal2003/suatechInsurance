<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            auth()->login($user);
            $url = '';
            if ($user->role == 'admin'){
                $url = '/' . $user->username ."/brokers";
            }elseif($user->role == 'broker') {
                $url = "/".$user->Broker->broker_name."/me/profile";
            }else {
                $url = "/".$user->client->client_name."/profile";
            }
            return response()->json([
                "redirect_url" => $url,
                'success' => 'Login successful',
            ], 200);
        } else {
            return response()->json([
                'error' => 'Invalid username or password'
            ], 401);
        }
    }
    public function logout()
    {
        Auth::logout();
        // if(Auth::user()->username){
        //     return 'not logout';
        // }else {
        return redirect()->route('login.page');
        // }
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->filled('role')) {
            $query->where('role', 'like', '%' . $request->role . '%');
        }
        if (Auth::user()->role == 'broker') {
            $query->where('created_by', Auth::user()->id);
        }
        $users = $query->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $req)
    {
        $validated = $req->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string',
            'role' => Auth::user()->role != 'broker' ? 'required|string' : 'required|string|in:client',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'created_by' => Auth::user()->role == 'broker' ? Auth::user()->id : null,
        ]);

        return response()->json([
            "success" => "User created successfully",
        ]);
    }

    public function edit($username, $id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $req, $username, $id)
    {
        $user = User::findOrFail($id);



        $validated = $req->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string',
            'role' => 'required|string',
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            "success" => "User updated successfully",
        ]);
    }
}
