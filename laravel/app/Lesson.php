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
        'title', 'description', 'user_id', 'topics'
    ];
    
    protected $casts = [
        'topics' => 'array'
    ];
    
    public function author()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
