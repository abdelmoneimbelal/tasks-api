<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{

    use  SoftDeletes;

    protected $fillable = ['title', 'user_id', 'description'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }

    public function comments(){
        return $this->hasManyThrough(Comment::class, Task::class);
    }

    public function files(){
        return $this->hasManyThrough(File::class, Task::class);
    }

}
