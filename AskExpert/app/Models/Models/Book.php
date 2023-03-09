<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $table = "book";
    protected $fillable = ['user_id', 'expert_id', 'appointment_id'];


    ######################## Begin relations ##################

    public function user(){
        return $this -> belongsTo('App\Models\User', 'user_id');
    }

    public function expert(){
        return $this -> belongsTo('App\Models\Models\Expert', 'expert_id');
    }

    public function appointment(){
        return $this -> belongsTo('App\Models\Models\Appointment', 'appointment_id');
    }

    ######################## end relations ##################
    
}
