<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['reporter_id', 'category', 'location', 'comment', 'filed_by'];

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
}
