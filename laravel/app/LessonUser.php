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
    
    protected $appends = array('rate');

    public function getRateAttribute()
    {
        $topics = Topic::where('lesson_id', $this->lesson_id)->get('id');
        $ids = [];
        $count = 0;
        foreach ($topics as $item) {
            $ids[] = $item->id;
            $count++;
        }
        $maxScore = 5 * $count;
        $rates = RateFriend::where('friend_id',$this->user_id)->whereIn('topic_id', $ids)->get('value');
        $avg = $rates->avg('value');
        $currentScore = 0;
        $ratesA = [];
        if ($rates) {
            foreach ($rates as $rate) {
                $currentScore =$currentScore + $rate->value;
                $ratesA[] = $currentScore;
            }
        }
        $percent = ($currentScore / $maxScore) * 100;
        return [
            'percent' => $percent,
            'avg_rate' => round($avg,2)
            ];
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
