<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['reporter_id', 'category', 'location', 'comment', 'finalized', 'filed_by'];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function filedBy()
    {
        return $this->belongsTo(User::class, 'filed_by');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function format()
    {
        return [
            'id' => $this->id,
            'by' => [
                'id' => $this->reporter->id,
                'name' => $this->reporter->name
            ],
            'category' => $this->category,
            'location' => $this->location,
            'comment' => $this->comment,
            'finalized' => $this->finalized == 1,
            'students' => $this->students->map(fn($s) => [
                'id' => $s->id, 
                'firstName' => $s->firstName, 
                'lastName' => $s->lastName, 
                'level' => $s->level, 
                'section' => $s->section
            ]),
            'filedBy' => $this->filed_by ?  [
                'id' => $this->filedBy->id,
                'name' => $this->filedBy->name
            ] : null,
            'on' => $this->created_at
        ];
    }
}
