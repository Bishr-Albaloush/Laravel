<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = "categories";
    protected $fillable = ['name', 'image'];

     
    ######################## Begin relations ##################
    
    public function experts(){
        return $this -> hasMany('App\Models\Models\Expert', 'category_id');
    }

    public function questions(){
        return $this -> hasMany('App\Models\Models\Question', 'category_id');
    }

    ######################## end relations ##################
}
