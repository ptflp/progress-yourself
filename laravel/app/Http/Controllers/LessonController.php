<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lesson;
use App\Topic;
use App\LessonUser;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::with('author')->with('friends')->with('friends.user')->get();
        return $lessons;
    }
    
    public function my(Request $request)
    {
        $request->validate([
            'uid' => 'required',
        ]);
        $params = $request->all();
        $lessons = LessonUser::where('user_id', 1 )->with('lesson.author')->get();
        return $lessons;
    }
    
    public function join(Request $request, $id)
    {
        $params = $request->all();
        $lesson = Lesson::findOrFail($id);
        if ($lesson) {
            $friend = LessonUser::where('user_id',$params['uid'])->where('lesson_id', $lesson->id);
            if (!$friend) {
                $friend = new LessonUser;
                $friend->user_id = $params['uid'];
                $friend->lesson_id = $lesson->id;
                $friend->save();
            }
            $lesson->load('friends.user');
        }
        return $lesson;
    }
 
    public function show($id)
    {
        return Lesson::find($id);
    }

    public function store(Request $request)
    {
        $lessonData = $request->all();
        $lesson = Lesson::create($lessonData);
        $data = [];
        foreach ($lessonData['topics'] as $item) {
            $data[] = [
                'lesson_id' => $lesson->id,
                'title' => $item
                ];
        }
        Topic::insert($data);
        $params = [];
        $params['user_id'] = $lesson->user_id;
        $params['lesson_id'] = $lesson->id;
        LessonUser::create($params);
        $lesson->load('author');
        $lesson->load('topics');
        $lesson->load('friends.user');
        return $lesson;
    }

    public function update(Request $request, $id)
    {
        $article = Lesson::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

    public function delete(Request $request, $id)
    {
        $article = Lesson::findOrFail($id);
        $article->delete();

        return 204;
    }
    
}
