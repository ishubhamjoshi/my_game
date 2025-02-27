<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResultHistory;

class ResultHistoryController extends Controller
{
    public function index()
    {
        return view('result_history.index');
    }

    public function getData(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');

        $query = ResultHistory::whereDate('date', $date);

        $totalRecords = $query->count();

        $filteredRecords = $query->count();

        $drawResults = $query->orderBy('time', 'asc')
            ->skip($start)
            ->take($length)
            ->get()
            ->map(function ($item) {
                $item->formatted_time = date('h:i A', strtotime($item->time));
                return $item;
            });

        $time = $drawResults->pluck('time')->toArray();

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $drawResults,
            "time" => $time,
        ]);
    }

}
