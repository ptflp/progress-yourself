<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        'title', 'lesson_id'
    ];
    
    protected $hidden = [
        'lesson_id', 'created_at', 'updated_at'
    ];
    
    public function lesson()
    {
        return $this->belongsTo('App\Lesson', 'lesson_id', 'id');
    }
}
