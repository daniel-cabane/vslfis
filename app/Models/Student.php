<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['firstName', 'lastName', 'email', 'tagNb', 'level', 'section', 'status', 'details'];

    protected function casts(): array
    {
        return [
            'details' => 'object'
        ];
    }
}
