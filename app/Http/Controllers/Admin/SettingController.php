<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\SettingStoreRequest;
use App\Http\Requests\Settings\SettingsRequest;
use App\Models\Setting;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::query()->paginate(5);
        return view('admin.settings.list', compact('settings'));
    }

    public function create()
    {
        return view('admin.settings.update2');
    }

    public function isFileExists(string $checkPath, string $fileName)
    {
        if (file_exists(public_path($checkPath . $fileName)))
        {
            return redirect()->back()->withErrors([
                'image' => "Bu resim daha öncede yüklenmiş"
            ]);
        }
    }

    public function store(SettingStoreRequest $request)
    {
        $folder = "images";
        $checkPath = "storage/" . $folder . '/';

        if (!is_null($request->logo_image))
        {
            $logoImage = $request->file('logo_image');

            $logoOriginalName = $logoImage->getClientOriginalName();
            $logoOriginalExtension = $logoImage->getClientOriginalExtension();
            $logoFileName = Str::slug(explode(".", $logoOriginalName)[0]). "." . $logoOriginalExtension;

//            if (file_exists(public_path($checkPath . $logoFileName)))
//            {
//                return redirect()->back()->withErrors([
//                    'logo_image' => "Bu Logo görseli daha önce yüklenmiş"
//                ]);
//            }
            $this->isFileExists($checkPath, $logoFileName);
        }
        if (!is_null($request->home_slider_image))
        {
            $homeSliderImage = $request->file('home_slider_image');

            $sliderOriginalName = $homeSliderImage->getClientOriginalName();
            $sliderOriginalExtension = $homeSliderImage->getClientOriginalExtension();
            $sliderFileName = Str::slug(explode(".", $sliderOriginalName)[0]) . "." . $sliderOriginalExtension;

//            if (file_exists(public_path($checkPath . $sliderFileName)))
//            {
//                return redirect()->back()->withErrors([
//                    'home_slider_image' => "Bu Slider görseli daha önce yüklenmiş"
//                ]);
//            }
            $this->isFileExists($checkPath, $sliderFileName);
        }
        if (!is_null($request->adverse_image))
        {
            $adverseImage = $request->file('adverse_image');

            $adverseImageOriginalName = $adverseImage->getClientOriginalName();
            $adverseImageOriginalExtension = $adverseImage->getClientOriginalExtension();
            $adverseImageFileName = Str::slug(explode(".", $adverseImageOriginalName)[0]) . "." . $adverseImageOriginalExtension;

//            if (file_exists(public_path($checkPath . $adverseImageFileName)))
//            {
//                return redirect()->back()->withErrors([
//                    'adverse_image' => "Bu Reklam görseli daha önce yüklenmiş"
//                ]);
//            }
            $this->isFileExists($checkPath, $adverseImageFileName);
        }

        try {
            Setting::create([
                'logo_image' => !is_null($request->logo_image) ? $checkPath . $logoFileName : null,
                'home_slider_image' => !is_null($request->home_slider_image) ? $checkPath . $sliderFileName : null,
                'adverse_image' => !is_null($request->adverse_image) ? $checkPath . $adverseImageFileName : null
            ]);
            if (!is_null($request->logo_image))
                $logoImage->storeAs($folder, $logoFileName, 'public');
            if (!is_null($request->home_slider_image))
                $homeSliderImage->storeAs($folder, $sliderFileName, 'public');
            if (!is_null($request->adverse_image))
                $adverseImage->storeAs($folder, $adverseImageFileName, 'public');
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }

        alert()->success('Başarılı', "Site Görselleri Eklendi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route('setting.index');
    }

    public function edit(Request $request, int $settingID)
    {
        $setting = Setting::query()->where('id', $settingID)->first();

        if (is_null($setting))
        {
            alert()->error('Hata', 'Aradığınız Görseller bulunamadı')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
            return redirect()->back();
        }
        return view('admin.settings.update2', compact('setting'));
    }

    public function update(SettingsRequest $request, int $settingID)
    {
//    dd($request->logo_image, $request->home_slider_image, $request->adverse_image);

        $settingQuery = Setting::query()->where('id', $settingID);

        $settingOld = $settingQuery->first();
//        dd($settingOld->logo_image);

        $folder = "images";
        $checkPath = "storage/" . $folder . '/';

      if (!is_null($request->logo_image))
      {
          $logoImage = $request->file('logo_image');

          $logoOriginalName = $logoImage->getClientOriginalName();
          $logoOriginalExtension = $logoImage->getClientOriginalExtension();
          $logoFileName = Str::slug(explode(".", $logoOriginalName)[0]). "." . $logoOriginalExtension;

//          if (file_exists(public_path($checkPath . $logoFileName)))
//          {
//              return redirect()->back()->withErrors([
//                  'logo_image' => "Bu Logo görseli daha önce yüklenmiş"
//              ]);
//          }
          $this->isFileExists($checkPath, $logoFileName);
      }
        if (!is_null($request->home_slider_image))
        {
            $homeSliderImage = $request->file('home_slider_image');

            $sliderOriginalName = $homeSliderImage->getClientOriginalName();
            $sliderOriginalExtension = $homeSliderImage->getClientOriginalExtension();
            $sliderFileName = Str::slug(explode(".", $sliderOriginalName)[0]) . "." . $sliderOriginalExtension;

//            if (file_exists(public_path($checkPath . $sliderFileName)))
//            {
//                return redirect()->back()->withErrors([
//                    'home_slider_image' => "Bu Slider görseli daha önce yüklenmiş"
//                ]);
//            }
            $this->isFileExists($checkPath, $sliderFileName);
        }
        if (!is_null($request->adverse_image))
        {
            $adverseImage = $request->file('adverse_image');

            $adverseImageOriginalName = $adverseImage->getClientOriginalName();
            $adverseImageOriginalExtension = $adverseImage->getClientOriginalExtension();
            $adverseImageFileName = Str::slug(explode(".", $adverseImageOriginalName)[0]) . "." . $adverseImageOriginalExtension;

//            if (file_exists(public_path($checkPath . $adverseImageFileName)))
//            {
//                return redirect()->back()->withErrors([
//                    'adverse_image' => "Bu Reklam görseli daha önce yüklenmiş"
//                ]);
//            }
            $this->isFileExists($checkPath, $adverseImageFileName);
        }
        $data = [
            'logo_image' => !is_null($request->logo_image) ? $checkPath . $logoFileName : $settingOld->logo_image,
            'home_slider_image' => !is_null($request->home_slider_image) ? $checkPath . $sliderFileName : $settingOld->home_slider_image,
            'adverse_image' => !is_null($request->adverse_image) ? $checkPath . $adverseImageFileName : $settingOld->adverse_image
        ];

        try {
            $settingQuery->update($data);

            if (!is_null($request->logo_image))
            {
                if (file_exists(public_path($settingOld->logo_image)))
                {
                    \Illuminate\Support\Facades\File::delete(public_path($settingOld->logo_image));
                }
                $logoImage->storeAs($folder,  $logoFileName, 'public');
            }
            if (!is_null($request->home_slider_image))
            {
                if (file_exists(public_path($settingOld->home_slider_image)))
                {
                    \Illuminate\Support\Facades\File::delete(public_path($settingOld->home_slider_image));
                }
                $homeSliderImage->storeAs($folder,  $sliderFileName, 'public');
            }
            if (!is_null($request->adverse_image))
            {
                if (file_exists(public_path($settingOld->adverse_image)))
                {
                    \Illuminate\Support\Facades\File::delete(public_path($settingOld->adverse_image));
                }
                $adverseImage->storeAs($folder,  $adverseImageFileName, 'public');
            }
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }

        alert()->success('Başarılı', "Görseller güncellendi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route("setting.index");

    }

    public function delete(Request $request): \Illuminate\Http\JsonResponse
    {
        $settingID = $request->settingID;

        $setting = Setting::query()->find($settingID);

        if ($setting)
        {
            $setting->delete();

            return response()
                ->json(["status" => "success", "message"=>"Başarılı", "data"=>""])
                ->setStatusCode(200);
        }
        return response()
            ->json(["status" => "error", "message" => "Görsel Bulunamadı"])
            ->setStatusCode(404);
    }
}
