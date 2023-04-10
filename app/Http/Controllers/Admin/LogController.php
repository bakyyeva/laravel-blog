<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::query()->with(['loggable', 'user'])->paginate(20);

        return view('admin.logs.list', compact('logs'));
    }

    public function getLog(Request $request, int $ID)
    {
        $dataType = $request->data_type;

        $log = Log::query()->with('loggable')->findOrFail($ID);

        $logtype = $log->loggable_type;

        $data = json_decode($log->data);

        if ($dataType == "data")
        {
            return response()->json()->setData($data)->setStatusCode(200);
        }

        $data = $log->loggable;
        return view('admin.logs.model-log-view', compact("data", 'logtype'));
    }

}
