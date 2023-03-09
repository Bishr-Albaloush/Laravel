<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $table = "answers";
    protected $fillable = ['question_id', 'expert_id', 'text'];


    ######################## Begin relations ##################

    public function expert(){
        return $this -> belongsTo('App\Models\Models\Expert', 'expert_id');
    }

    public function question(){
        return $this -> belongsTo('App\Models\Models\Question', 'question_id');
    }
    
    ######################## end relations ##################
    
}
