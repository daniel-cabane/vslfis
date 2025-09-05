<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Student;

class AdminController extends Controller
{
    public function indexUsers()
    {
        return response()->json([
            'users' => User::where('id', '!=', 1)->get()
        ]);
    }

    public function recentUsers()
    {
        return response()->json([
            'users' => User::where('id', '!=', 1)->orderBy('created_at', 'desc')->take(15)->get()
        ]);
    }

    public function searchUsers(Request $request)
    {
        $name = $request->validate(['name' => 'required|max:100'])['name'];

        return response()->json([
            'users' => User::where('id', '!=', 1)
                ->where(function($query) use ($name) {
                    $query->where('name', 'like', "%$name%")
                        ->orWhere('email', 'like', "%$name%");
                })
                ->take(15)
                ->get()
        ]);
    }

    public function updateUser(User $user, Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'isCPE' => 'required|boolean',
        ]);

        $user->update([
            'name' => $attrs['name'], 'email' => $attrs['email']
        ]);

        if(!$user->is['cpe'] && $attrs['isCPE']){
            $user->assignRole('cpe');
        }
        if($user->is['cpe'] && !$attrs['isCPE']){
            $user->removeRole('cpe');
        }

        return response()->json([
            'user' => $user,
            'message' => [
                        'text' => 'User updated',
                        'type' => 'success'
                    ]
        ]);
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => [
                        'text' => 'User deleted',
                        'type' => 'info'
                    ]
        ]);
    }

    public function addStudents(Request $request)
    {
        $attrs = $request->validate([
            'students' => 'required|Array'
        ]);

        $added = 0;
        $duplicates = 0;
        foreach($attrs['students'] as $student){
            if(Student::where('email', $student['email'])->exists()){
                $duplicates++;
            } else {
                Student::create($student);
                $added++;
            }
        }
        if($added <= 1){
            $msg = "$added student added";
        } else {
            $msg = "$added students added";
        }
        if($duplicates == 1){
            $msg .= " - 1 duplicate, not added.";
        }
        if($duplicates >1){
            $msg .= " - $duplicates duplicates, not added.";
        }

        return response()->json([
            'success' => true,
            'message' => [
                        'text' => $msg,
                        'type' => 'success'
                    ]
        ]);
    }

    public function badgelessStudents()
    {
        return response()->json([
            'students' => Student::where('tagNb', null)->get()
        ]);
    }

    public function searchStudents(Request $request)
    {
        $name = $request->validate(['name' => 'required|max:100'])['name'];

        $students = Student::when($name, function ($queryBuilder) use ($name) {
            return $queryBuilder->where(function ($query) use ($name) {
                $query->where('firstName', 'LIKE', "%{$name}%")
                      ->orWhere('email', 'like', "%$name%")
                      ->orWhere('lastName', 'LIKE', "%{$name}%")
                      ->orWhere(DB::raw("CONCAT(lastName, ' ', firstName)"), 'LIKE', "%{$name}%")
                      ->orWhere(DB::raw("CONCAT(firstName, ' ', lastName)"), 'LIKE', "%{$name}%");
            });
        })->get();

        return response()->json(['students' => $students]);
    }

    public function updateStudent(Student $student, Request $request)
    {
        $attrs = $request->validate([
            'lastName' => 'required|String|max:40',
            'firstName' => 'required|String|max:40',
            'email' => 'required|Email|max:100',
            'tagNb' => 'sometimes|Integer|nullable',
            'level' => 'required|String|max:10',
            'section' => 'required|String|max:4',
            'status' => 'required|String|max:40',
        ]);

        $student->update($attrs);

        return response()->json([
            'success' => true,
            'student' => $student,
            'message' => [
                        'text' => 'Student updated',
                        'type' => 'success'
                    ]
        ]);
    }

    public function updateStudentPhoto(Student $student, Request $request)
    {
        $request->validate([
        'photo' => 'required|image|max:512' // max 512 KB
    ]);

    $path = $request->file('photo')->store('students', 'public');
    $student->photo = '/storage/' . $path;
    $student->save();

    return response()->json([
            'success' => true,
            'student' => $student,
            'message' => [
                'text' => 'Photo updated',
                'type' => 'success'
            ]
        ]);
    }

    public function deleteStudent(Student $student)
    {
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => [
                'text' => 'Student deleted',
                'type' => 'info'
            ]
        ]);
    }
}
