<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student;

class StudentController extends Controller
{
    public function searchStudents(Request $request)
    {
        $name = $request->validate(['name' => 'required|max:100'])['name'];

        $students = Student::when($name, function ($queryBuilder) use ($name) {
            return $queryBuilder->where(function ($query) use ($name) {
                $query->where('firstName', 'LIKE', "%{$name}%")
                      ->orWhere('email', 'like', "%$name%")
                      ->orWhere('lastName', 'LIKE', "%{$name}%")
                      ->orWhere(DB::raw("CONCAT(firstName, ' ', lastName)"), 'LIKE', "%{$name}%");
            });
        })->take(15)->get();

        return response()->json(['students' => $students]);
    }

    public function byTag(Request $request)
    {
        $tag = $request->validate(['tag' => 'required|integer'])['tag'];

        $student = Student::where('tagNb', $tag)->first();

        if($student){
            return response()->json([
                'student' => $student,
                'message' => [
                            'text' => 'Scan successful',
                            'type' => 'success'
                        ]
            ]);
        }
        return response()->json([
                'message' => [
                            'text' => 'Could not find student',
                            'type' => 'error'
                        ]
            ]);
    }
}
