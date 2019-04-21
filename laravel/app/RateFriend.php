<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateFriend extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'topic_id', 'rater_id', 'friend_id'
    ];
}
