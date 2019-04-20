<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }
 
    public function show($id)
    {
        return User::find($id);
    }

    public function store(Request $request)
    {
        return User::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $article = User::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

    public function delete(Request $request, $id)
    {
        $article = User::findOrFail($id);
        $article->delete();

        return 204;
    }
    
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'input_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'uid' => 'required'
        ]);
        $params = $request->all();
        if ($request->hasFile('input_img')) {
            $id = $params['uid'];
            $image = $request->file('input_img');
            $name = time().'.'.$image->getClientOriginalExtension();
            $path = '/images/user/' . $id;
            $destinationPath = public_path($path);
            $succes = $image->move($destinationPath, $name);
            $dataPath = $path . "/" . $name;
            $user = User::findOrFail($id);
            $user->avatar = $dataPath;
            $user->save();
            return $user;
        }
    }
}
