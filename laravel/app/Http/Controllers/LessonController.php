<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lesson;
use App\Topic;
use App\LessonUser;
use App\RateFriend;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::with('author')->with('friends')->with('topics')->with('friends.user')->get();
        return $lessons;
    }
    
    public function my(Request $request)
    {
        $request->validate([
            'uid' => 'required',
        ]);
        $params = $request->all();
        $lessons = LessonUser::where('user_id', 1 )
        ->with('lesson.author')
        ->with('lesson.topics')
        ->with(['lesson.topics.rates' => function($q) use ($params) {
           $q->where("friend_id","=",$params['uid']);
        }])
        ->with('lesson.friends.user')
        ->get();
        $avgs = [];
        foreach ($lessons as $keyL => $lesson) {
            $avgL = 0;
            $index = 0;
            $percent = 0;
            foreach ($lesson->lesson->topics as $keyT => $topic) {
                $avg = 0;
                $i = 0;
                if (count($topic->rates)>0) {
                    foreach($topic->rates as $keyR => $rate) {
                        $avg += $rate->value;
                        $i++;
                    }
                    $avg = $avg / $i;
                    $lessons[$keyL]->lesson->topics[$keyT]->avg = $avg;
                    $avgL += $avg;
                    $index++;
                }
            }
            if ($index) {
                $avgL = $avgL / $index;
                $percent = $avgL * 100 / ($index * 5);
            }
            $lessons[$keyL]->lesson->avg = $avgL;
            $lessons[$keyL]->lesson->percent = $percent;
        }
        
        
        return $lessons;
    }
    
    public function join(Request $request, $id)
    {
        $params = $request->all();
        $lesson = Lesson::findOrFail($id);
        if ($lesson) {
            $friend = LessonUser::where('user_id',$params['uid'])->where('lesson_id', $lesson->id)->first();
            if (!$friend) {
                $friend = new LessonUser;
                $friend->user_id = $params['uid'];
                $friend->lesson_id = $lesson->id;
                $friend->save();
            }
            $lesson->load('friends.user');
            $lesson->load('topics');
        }
        return $lesson;
    }
 
    public function showRate(Request $request)
    {
        $request->validate([
            'uid' => 'required',
            'user_id' => 'required',
            'topic_id' => 'required',
        ]);
        $params = $request->all();
        
        $rate = RateFriend::where('topic_id',$params['topic_id'])->where("rater_id","=",$params['uid'])->where("friend_id","=",$params['user_id'])->first();
        
        return ["value"=> $rate->value];
    }
    
    public function show($id)
    {
        return Lesson::where('id',$id)->with('friends.user')->with('author')->with('topics')->first();
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
