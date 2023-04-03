<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialMedia\SocialMediaCreateRequest;
use App\Http\Requests\SocialMedia\SocialMediaUpdateRequest;
use App\Models\SocialMedia;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index()
    {
        $list = SocialMedia::query()->paginate(5);

        return view('admin.social-media.list', compact('list'));
    }

    public function create()
    {
        return view('admin.social-media.create-update');
    }

    public function store(SocialMediaCreateRequest $request)
    {
//        dd($request->all());
        try {
            SocialMedia::create([
                'name' => $request->name,
                'icon' => $request->icon,
                'status' => $request->status ? 1 : 0,
                'link' => $request->link,
                'order' => $request->order
            ]);
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }
        alert()->success('Başarılı', "Sosyal Medya Kaydedildi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route('social-media.index');
    }

    public function changeStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $socialMediaID = $request->mediaID;

        $socialMedia = SocialMedia::query()->find($socialMediaID);

        if ($socialMedia)
        {
            $socialMedia->status = $socialMedia->status ? 0 : 1;
            $socialMedia->save();

            return response()
                ->json(['status' => "success", "message" => "Başarılı", "data" => $socialMedia, "social_media_status" => $socialMedia->status])
                ->setStatusCode(200);
        }
        return response()
            ->json(['status' => "error", "message" => "Sosyal Medya bulunamadı"])
            ->setStatusCode(404);
    }

    public function delete(Request $request): \Illuminate\Http\JsonResponse
    {
        $socialMediaID = $request->mediaID;

        $socialMedia = SocialMedia::query()->find($socialMediaID);

        if ($socialMedia)
        {
            $socialMedia->delete();

            return response()
                ->json(["status" => "success", "message"=>"Başarılı", "data"=>""])
                ->setStatusCode(200);
        }
        return response()
            ->json(["status" => "error", "message" => "Sosyal Medya Bulunamadı"])
            ->setStatusCode(404);
    }

    public function edit(Request $request, int $socialMediaId)
    {
        $socialMedia = SocialMedia::query()->find($socialMediaId);

        if (is_null($socialMedia))
        {
            alert()->error('Hata', 'Sosyal Medya bulunamadı')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
            return redirect()->back();
        }
        return view('admin.social-media.create-update', ['socialMedia' => $socialMedia]);

    }

    public function update(SocialMediaUpdateRequest $request, int $socialMediaId)
    {
        $socialMedia = SocialMedia::query()->find($socialMediaId);

        try {
                $socialMedia->icon = $request->icon;
                $socialMedia->name = $request->name;
                $socialMedia->status = $request->status ? 1 : 0;
                $socialMedia->link = $request->link;
                $socialMedia->order = $request->order;

                $socialMedia->save();
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }
        alert()->success('Bşarılı', 'Sosyal Medya Güncellendi')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route('social-media.index');
    }
}
