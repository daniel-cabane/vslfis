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

    public function unfinalized()
    {
        return response()->json([
            'reports' => Report::where('finalized', 0)->get()->map(fn($r) => $r->format())
        ]);
    }

    public function filable()
    {
        return response()->json([
            'reports' => Report::where('finalized', 1)->where('filed_by', null)->get()->map(fn($r) => $r->format())
        ]);
    }

    public function filed()
    {
        return response()->json([
            'reports' => Report::where('filed_by', '!=', null)->take(100)->get()->map(fn($r) => $r->format())
        ]);
    }

    public function myReports()
    {
        return response()->json([
            'reports' => auth()->user()->reports->map(fn($r) => $r->format())
        ]);
    }

    public function myUnfinalized()
    {
        return response()->json([
            'reports' => auth()->user()->reports()->where('finalized', 0)->get()->map(fn($r) => $r->format())
        ]);
    }

    public function store(Request $request)
    {
        $attrs = $request->validate([
            'category' => 'required|max:50',
            'students' => 'sometimes|array|nullable',
            'location' => 'sometimes|max:100|nullable',
            'comment' => 'sometimes|max:500|nullable',
            'finalized' => 'required|boolean'
        ]);

        $report = Report::create([
            'reporter_id' => auth()->id(),
            'category' => $attrs['category'],
            'location' => $attrs['location'],
            'comment' => $attrs['comment'],
            'finalized' => $attrs['finalized']
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
            'comment' => 'sometimes|max:500|nullable',
            'finalized' => 'required|boolean'
        ]);

        $report->update([
            'reporter_id' => auth()->id(),
            'category' => $attrs['category'],
            'location' => $attrs['location'],
            'comment' => $attrs['comment'],
            'finalized' => $attrs['finalized']
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

    public function destroy(Report $report)
    {
        $report->delete();

        return response()->json([
            'success' => true,
            'message' => [
                        'text' => 'Report deleted',
                        'type' => 'info'
                    ]
        ]);
    }

    public function file(Report $report)
    {
        if(!$report->finalized){
            return response()->json([
            'message' => [
                        'text' => 'Report has to be finalized to be filed',
                        'type' => 'error'
                    ]
            ]);
        }

        $report->update([
            'filed_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'report' => $report->format(),
            'message' => [
                        'text' => 'Report marked as filed on Pronote',
                        'type' => 'success'
                    ]
        ]);
    }

    public function unfile(Report $report)
    {
        $report->update([
            'filed_by' => null
        ]);

        return response()->json([
            'success' => true,
            'report' => $report->format(),
            'message' => [
                        'text' => 'Report marked as unfiled',
                        'type' => 'success'
                    ]
        ]);
    }
}
