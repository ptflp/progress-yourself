<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'user_id'
    ];
    
    protected $casts = [
        'topics' => 'array'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id'
    ];
    
    public function author()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    
    public function friends()
    {
        return $this->hasMany('App\LessonUser', 'lesson_id', 'id');
    }
    
    public function topics()
    {
        return $this->hasMany('App\Topic', 'lesson_id', 'id');
    }
}
