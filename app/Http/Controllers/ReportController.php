<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
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
            'report' => $report,
            'message' => [
                        'text' => 'Report submitted',
                        'type' => 'success'
                    ]
        ]);
    }
}
