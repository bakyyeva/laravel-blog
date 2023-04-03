<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Log\LogFilterRequst;
use App\Http\Requests\Log\LogIdRequest;
use App\Http\Requests\Log\LogUpdateRequest;
use App\Models\Category;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(LogFilterRequst $request)
    {
        $data = $request->all();
        $users = User::all();
        $logs = Log::query()
            ->with('user:id,name')
            ->processID($request->process_id)
            ->processType($request->process_type)
            ->createdLog($request->created_log)
            ->updatedLog($request->updated_log)
            ->user($request->user_id)
            ->paginate(5);

        return view('admin.logs.list', compact('logs', 'users' ,'data'));
    }

    public function create()
    {
        return "log ekle";
        $users = User::all();
        return view('admin.logs.create-update', ['users' => $users]);
    }

    public function edit(Request $request, int $logID)
    {
        $users = User::all();
        $log = Log::query()->where('id', $logID)->first();

        if (is_null($log))
        {
            alert()->error('Hata', 'Log bulunamadı')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
            return redirect()->back();
        }
        return view('admin.logs.create-update', compact('log', 'users'));
    }

    public function update(LogUpdateRequest $request, int $logID)
    {
        $log = Log::query()->find($logID);

        $log->user_id = $request->user_id;
        $log->process_type = $request->process_type;
        $log->process_id = $request->process_id;

        try {
            $log->save();
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }

        alert()->success('Başarılı', "Log güncellendi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route("log.index");
    }

    public function delete(Request $request): \Illuminate\Http\JsonResponse
    {
        $logID = $request->logID;

        $log = Log::query()->find($logID);

        if ($log)
        {
            $log->delete();

            return response()
                ->json(["status" => "success", "message"=>"Başarılı", "data"=>""])
                ->setStatusCode(200);
        }
        return response()
            ->json(["status" => "error", "message" => "Log Bulunamadı"])
            ->setStatusCode(404);
    }
}
