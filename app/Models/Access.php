<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $fillable = ['student_id', 'authorized_by', 'direction'];

    public function by()
    {
        return $this->belongsTo(User::class, 'authorized_by');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function format()
    {
        $user = auth()->user();
        return [
            'id' => $this->id,
            'by' => [
                'id' => $this->by->id,
                'name' => $this->by->name
            ],
            'student' => [
                'id' => $this->student->id, 
                'firstName' => $this->student->firstName, 
                'lastName' => $this->student->lastName, 
                'level' => $this->student->level, 
                'section' => $this->student->section,
                'status' => $this->student->status
            ],
            'deletable' => $this->by->id == $user->id || $user->is['cpe'] || $user->is['admin'],
            'direction' => $this->direction,
            'on' => $this->created_at
        ];
    }
}
