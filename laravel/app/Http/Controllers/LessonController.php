<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lesson;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::with('author')->get();
        return $lessons;
    }
 
    public function show($id)
    {
        return Lesson::find($id);
    }

    public function store(Request $request)
    {
        $lesson = Lesson::create($request->all());
        $lesson->load('author');
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
