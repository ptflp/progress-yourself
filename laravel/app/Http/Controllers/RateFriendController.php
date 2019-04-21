<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RateFriend;

class RateFriendController extends Controller
{
    public function index()
    {
        return RateFriend::all();
    }
 
    public function show($id)
    {
        return RateFriend::find($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'topic_id' => 'required',
            'uid' => 'required',
            'friend_id' => 'required',
            'value' => 'required',
        ]);
        
        $params = $request->all();
        
        $params['rater_id'] = $params['uid'];
        
        $rateFriend = RateFriend::where('friend_id',$params['friend_id'])
        ->where('rater_id', $params['rater_id'])
        ->where('topic_id', $params['topic_id'])
        ->first();
        
        if ($rateFriend) {
            $rateFriend->value = $params['value'];
            $rateFriend->update();
        } else {
            $rateFriend = RateFriend::create($params);
        }
        
        return $rateFriend;
    }

    public function update(Request $request, $id)
    {
        $model = RateFriend::findOrFail($id);
        $model->update($request->all());

        return $model;
    }

    public function delete(Request $request, $id)
    {
        $model = RateFriend::findOrFail($id);
        $model->delete();

        return 204;
    }
}
