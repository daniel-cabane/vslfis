<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BugReport;

class BugReportController extends Controller
{
    public function index()
    {
        return response()->json([
            'reports' => auth()->user()->hasRole('admin') ? BugReport::where('status', 'open')->with(['author', 'student'])->get() : []
        ]);
    }

    public function store(Request $request)
    {
        $attrs = $request->validate([
            'student' => 'sometimes|integer|nullable',
            'comment' => 'required|max:1000'
        ]);

        BugReport::create([
            'author_id' => auth()->id(),
            'student_id' => isset($attrs['student']) && $attrs['student'] != 0 ? $attrs['student'] : null,
            'comment' => $attrs['comment']
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                        'text' => 'Report submitted',
                        'type' => 'success'
                    ]
        ]);
    }

    public function update(BugReport $bugreport)
    {
        $bugreport->update(['status' => 'closed']);

        return response()->json([
            'success' => true,
            'message' => [
                        'text' => 'Report closed',
                        'type' => 'success'
                    ]
        ]);
    }

    public function destroy(BugReport $bugreport)
    {
        $bugreport->delete();

        return response()->json([
            'success' => true,
            'message' => [
                        'text' => 'Report deleted',
                        'type' => 'success'
                    ]
        ]);
    }
}
