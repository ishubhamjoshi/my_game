<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DrawResult;
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

        $query = DrawResult::whereDate('date', $date);

        $totalRecords = $query->count();

        // Apply pagination
        $drawResults = $query->orderBy('time', 'asc')
            ->get()
            ->map(function ($item) {
                $item->formatted_time = date('h:i A', strtotime($item->time));
                return $item;
            });

        $time = $drawResults->pluck('time')->toArray();

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "data" => $drawResults,
            "time" => $time,
        ]);
    }

    public function resultIndex()
    {
        return view('result_history.index');
    }

    public function getResultData(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $draw = (int) $request->input('draw', 1);

        $query = DrawResult::whereDate('date', $date);

        if ($date == now()->format('Y-m-d')) {
            $currentTime = now()->setTimezone('Asia/Kolkata')->format('H:i:00');
            $query->whereTime('time', '<=', $currentTime);
        }

        $totalRecords = $query->count();

        $drawResults = $query->orderBy('time', 'asc')
            ->get()
            ->map(function ($item) {
                $item->formatted_time = date('h:i A', strtotime($item->time));
                return $item;
            });

        return response()->json([
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "data" => $drawResults,
            "time" => $drawResults->pluck('time')->toArray(),
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
