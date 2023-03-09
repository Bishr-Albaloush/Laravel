<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    use HasFactory;
    protected $table = "experts";
    protected $fillable = ['user_id', 'experience', 'category_id', 'phone', 'image', 'address'];
    

    ######################## Begin relations ##################
    
    public function books(){
        return $this -> hasMany('App\Models\Models\Book', 'expert_id');
    }

    public function questions(){
        return $this -> hasMany('App\Models\Models\Question', 'expert_id');
    }

    public function appointments(){
        return $this -> hasMany('App\Models\Models\Appointment', 'expert_id');
    }

    public function answers(){
        return $this -> hasMany('App\Models\Models\Answer', 'expert_id');
    }

    public function user(){
        return $this -> belongsTo('App\Models\User', 'user_id');
    }

    public function category(){
        return $this -> belongsTo('App\Models\Models\Category', 'expert_id');
    }
    
    ######################## end relations ##################
    
}
