<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model 
{
    use HasFactory;
    protected $table = "appointment";
    protected $fillable = ['expert_id', 'date', 'time', 'available', 'price'];


    ######################## Begin relations ##################

    public function book(){
        return $this -> hasOne('App\Models\Models\Book', 'appointment_id');
    }

    public function expert(){
        return $this -> belongsTo('App\Models\Models\Expert', 'expert_id');
    }

    ######################## end relations ##################
}
