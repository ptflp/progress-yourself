<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\RateFriend;

class LessonUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lesson_id', 'user_id'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'lesson_id', 'user_id', 'id'
    ];
    
    protected $appends = array('rate_avg');

    public function getRateAvgAttribute()
    {
        $topics = Topic::where('lesson_id', $this->lesson_id)->get('id');
        $ids = [];
        foreach ($topics as $item) {
            $ids[] = $item->id;
        }
        return $ids;
        // return RateFriend::where('friend_id',$this->user_id)->get('id');
    }
    
    public function rules()
    {
        return [
            'lesson_id'  => 'required|unique_with:user_id',
            'user_id'  => 'required|unique_with:lesson_id',
        ];
    }
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    
    public function lesson()
    {
        return $this->belongsTo('App\Lesson', 'lesson_id', 'id');
    }
}
