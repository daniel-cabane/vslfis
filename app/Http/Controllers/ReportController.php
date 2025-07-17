<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        return response()->json([
            'reports' => Report::all()->map(fn($r) => $r->format())
        ]);
    }

    public function myReports()
    {
        return response()->json([
            'reports' => auth()->user()->reports->map(fn($r) => $r->format())
        ]);
    }

    public function store(Request $request)
    {
        $attrs = $request->validate([
            'category' => 'required|max:50',
            'students' => 'sometimes|array|nullable',
            'location' => 'sometimes|max:100|nullable',
            'comment' => 'sometimes|max:500|nullable'
        ]);

        $report = Report::create([
            'reporter_id' => auth()->id(),
            'category' => $attrs['category'],
            'location' => $attrs['location'],
            'comment' => $attrs['comment']
            
        ]);

        foreach($attrs['students'] as $student){
            $report->students()->attach($student);
        }

        return response()->json([
            'success' => true,
            'report' => $report->format(),
            'message' => [
                        'text' => 'Report submitted',
                        'type' => 'success'
                    ]
        ]);
    }

    public function update(Report $report, Request $request)
    {
        $attrs = $request->validate([
            'category' => 'required|max:50',
            'students' => 'sometimes|array|nullable',
            'location' => 'sometimes|max:100|nullable',
            'comment' => 'sometimes|max:500|nullable'
        ]);

        $report->update([
            'reporter_id' => auth()->id(),
            'category' => $attrs['category'],
            'location' => $attrs['location'],
            'comment' => $attrs['comment']
            
        ]);

        $report->students()->sync([]);
        foreach($attrs['students'] as $student){
            $report->students()->attach($student);
        }

        return response()->json([
            'success' => true,
            'report' => $report->format(),
            'message' => [
                        'text' => 'Report updated',
                        'type' => 'success'
                    ]
        ]);
    }
}
