<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BugReport extends Model
{
    protected $fillable = ['author_id', 'student_id', 'comment', 'status'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
