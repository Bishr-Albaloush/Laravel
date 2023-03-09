<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $table = "questions";
    protected $fillable = ['user_id', 'expert_id', 'category_id', 'text'];

    
    ######################## Begin relations ##################
    
    public function answer(){
        return $this -> hasOne('App\Models\Models\Answer', 'question_id');
    }
    
    public function user(){
        return $this -> belongsTo('App\Models\User', 'user_id');
    }

    public function expert(){
        return $this -> belongsTo('App\Models\Models\Expert', 'expert_id');
    }

    public function category(){
        return $this -> belongsTo('App\Models\Models\Category', 'category_id');
    }
    
    ######################## end relations ##################
    
}
