<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Access;

class AccessController extends Controller
{
    public function store(Student $student, Request $request)
    {
        $dir = $request->validate(['direction' => 'required|max:5'])['direction'];

        Access::create([
            'student_id' => $student->id,
            'authorized_by' => auth()->id(),
            'direction' => $dir
        ]);

        return response()->json([
            'message' => [
                'text' => $dir == 'out' ? "Exit recorded" : "Entry recorded",
                'type' => 'success'
            ]
        ]);
    }

    public function dayIndex(Request $request)
    {
        $date = $request->validate(['date' => 'required|date'])['date'];

        return response()->json([
            'accesses' => Access::whereDate('created_at', $date)->get()->map(fn($r) => $r->format())
        ]);
    }

    public function byStudent(Student $student)
    {
        $student->access = $student->accesses->groupBy(function ($access) {
                return $access->created_at->format('Y-m-d');
            })->map(function ($accesses) {
                return $accesses->map(function ($access) {
                    return $access->format();
                });
            });
        unset($student->accesses);
        return response()->json([
            'student' => $student
        ]);
    }

    public function destroy(Access $access)
    {
        $user = auth()->user();
        if(!($access->authorized_by == $user->id || $user->hasRole('cpe') || $user->hasRole('admin'))){
            return response()->json([
                'message' => [
                    'text' => 'You cannot delete this ressources',
                    'type' => 'error'
                ]
            ]);
        }

        $msg = $access->direction == 'in' ? 'Entry deleted' : 'Exit deleted';

        $access->delete();

        return response()->json([
            'success' => true,
            'message' => [
                'text' => $msg,
                'type' => 'info'
            ]
        ]);
    }
}
