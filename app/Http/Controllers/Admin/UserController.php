<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserIdRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->withTrashed()
            ->status($request->status)
            ->searchText($request->search_text)
            ->paginate(3);

        return view('admin.users.list', compact('users'));
    }

    public function edit(Request $request, User $user)
    {
//        dd($user);
//        $user = User::query()->where('id', $userID)->firstOrFail();

        return view('admin.users.create-update', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->except("_token");

        if(!is_null($data['password']))
        {
            $data['password'] = bcrypt($data["password"]);
        }
        else
        {
            unset($data["password"]);
        }
        $data['status'] = isset($data["status"]) ? 1 : 0;

        if (is_null($data['image']))
            $data['image'] = $user->image;

        try {
            $user->update($data);
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }
        alert()->success('Başarılı', "Kullanıcı Bilgileri Güncellendi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route('user.index');
    }

    public function create()
    {
        return view('admin.users.create-update');
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->except("_token");
        $data['password'] = bcrypt($data["password"]);
        $data['status'] = isset($data["status"]) ? 1 : 0;
        try {
            User::create($data);
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }
        alert()->success('Başarılı', "Kullanıcı Oluşturuldu")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route('user.index');
    }

    public function delete(UserIdRequest $request)
    {
        $user = User::query()->where('id', $request->id)->firstOrFail();

        $text = $user->name . " 'li kullanıcı silindi";
        $user->delete();

        alert()->success('Başarılı', $text)->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->back();
    }

    public function restore(UserIdRequest $request)
    {
        $user = User::withTrashed()->findOrFail($request->id);
        $text = $user->name . " 'li kullanıcı geri getirildi";
        $user->restore();

        alert()->success('Başarılı', $text)->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->back();
    }

    public function changeStatus(UserIdRequest $request)
    {
        $user = User::query()->where('id', $request->id)->firstOrFail();

        $oldStatus = $user->status;
        $user->status = !$user->status;
        $user->save();

        $statusText = ($oldStatus == 1 ? 'Aktif' : 'Pasif') . "'ten" . ($user->status == 1 ? ' Aktif' : ' Pasif');
        alert()->success('Başarılı', $user->title . ' status ' . $statusText . ' olarak güncellendi')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);

        return redirect()->back();
    }

    public function changeRememberToken(UserIdRequest $request)
    {
         $user = User::query()->where('id', $request->id)->first();

        if ($user->remember_token)
        {
            $user->remember_token = '';
        }
        else
        {
            $user->remember_token = Str::random(60);
        }

        $user->save();

        alert()->success('Başarılı', 'Remember token değiştirildi')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);

        return redirect()->back();

    }



}
