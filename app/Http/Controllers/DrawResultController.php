<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DrawResult;
use App\Models\ResultHistory;
use Validator;

class DrawResultController extends Controller
{
    public function index()
    {
        return view('draw_results.index');
    }

    public function getData(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');

        $query = DrawResult::whereDate('date', $date);

        $totalRecords = $query->count();

        $filteredRecords = $query->count();

        // Apply pagination
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

    public function storeData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'time' => 'required|string',
            'result' => ['required', 'regex:/^\d{2}$/'],
            'date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $convertedTime = date('H:i:s', strtotime($request->time));

        $drawResult = new DrawResult();
        $drawResult->date = $request->date;
        $drawResult->time = $convertedTime;
        $drawResult->result = strval($request->result);
        $drawResult->save();

        return response()->json([
            'status' => true,
            'message' => 'Draw result added successfully!',
            'data' => [
                'id' => $drawResult->id,
                'date' => $drawResult->date,
                'time' => date('h:i A', strtotime($drawResult->time)),
                'result' => $drawResult->result
            ]
        ]);
    }

    public function getTimes(Request $request)
    {
        $date = $request->date;

        $existingTimes = DrawResult::whereDate('date', $date)
            ->pluck('time')
            ->map(function ($time) {
                return date('g:i A', strtotime($time));
            })
            ->toArray();

        return response()->json(['times' => $existingTimes]);
    }

    public function getLatestDrawResult()
    {
        $now = now()->setTimezone('Asia/Kolkata');
        $currentTime = $now->format('H:i:00');
        $date = $now->format('Y-m-d');

        $times = DrawResult::whereDate('date', $date)->pluck('time')->toArray();

        if (in_array($currentTime, $times)) {
            $drawResult = DrawResult::whereDate('date', $date)->where('time', $currentTime)->orderBy('id', 'desc')->first();
            $resultHistory = ResultHistory::whereDate('date', $date)->where('time', $currentTime)->orderBy('id', 'desc')->first();
            if (empty($resultHistory)) {
                $resultHistory = new ResultHistory();
                $resultHistory->date = $drawResult->date;
                $resultHistory->time = $drawResult->time;
                $resultHistory->result = $drawResult->result;
                $resultHistory->save();
            }
        } else {
            $drawResult = DrawResult::whereDate('date', $date)->where('time', '<', $currentTime)->orderBy('time', 'desc')->first();
        }

        $result = strval($drawResult->result);
        $firstValue = (int)$result[0];
        $secondValue = (int)$result[1];

        return response()->json([
            'final' => $result,
            'andar' => $firstValue,
            'bahar' => $secondValue
        ]);
    }
}
