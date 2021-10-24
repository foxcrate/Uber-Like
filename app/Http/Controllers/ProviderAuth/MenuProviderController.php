<?php

namespace App\Http\Controllers\ProviderAuth;

use anlutro\LaravelSettings\ServiceProvider;
use App\Car;
use App\CarModel;
use App\Http\Controllers\ProviderResources\ProfileController;
use App\Mail\EilbazResetPasswordProvider;
use App\Mail\ProviderAccountConfirmation;
use App\Provider;
use App\ProviderService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MenuProviderController extends Controller
{
    //
    public function documents()
    {
        ProfileController::checkData();
        return view('provider.documents');
    }

    public function postDocuments(Request $request)
    {
        $this->validate($request, [
            'avatar' => 'nullable|image',
            'driver_licence_front' => 'nullable|image',
            'driver_licence_back' => 'nullable|image',
            'identity_front' => 'nullable|image',
            'identity_back' => 'nullable|image',
            'criminal_feat' => 'nullable|image',
            'drug_analysis_licence' => 'nullable|image',
        ]);

        $Provider = Provider::find(auth('provider')->user()->id);

        if ($request->hasFile('avatar')) {
            $avatar = $request->avatar;
            $avatar_new_name = time() . $avatar->getClientOriginalName();
            $avatar->move('uploads/provider', $avatar_new_name);

            $Provider->avatar = 'uploads/provider/' . $avatar_new_name;
            $Provider->save();
        }

        if ($request->hasFile('driver_licence_front')) {
            $driver_licence_front = $request->driver_licence_front;
            $driver_licence_front_new_name = time() . $driver_licence_front->getClientOriginalName();
            $driver_licence_front->move('uploads/provider', $driver_licence_front_new_name);

            $Provider->driver_licence_front = 'uploads/provider/' . $driver_licence_front_new_name;
            $Provider->save();
        }

        if ($request->hasFile('driver_licence_back')) {
            $driver_licence_back = $request->driver_licence_back;
            $driver_licence_back_new_name = time() . $driver_licence_back->getClientOriginalName();
            $driver_licence_back->move('uploads/provider', $driver_licence_back_new_name);

            $Provider->driver_licence_back = 'uploads/provider/' . $driver_licence_back_new_name;
            $Provider->save();
        }

        if ($request->hasFile('identity_front')) {
            $identity_front = $request->identity_front;
            $identity_front_new_name = time() . $identity_front->getClientOriginalName();
            $identity_front->move('uploads/provider', $identity_front_new_name);

            $Provider->identity_front = 'uploads/provider/' . $identity_front_new_name;
            $Provider->save();
        }

        if ($request->hasFile('identity_back')) {
            $identity_back = $request->identity_back;
            $identity_back_new_name = time() . $identity_back->getClientOriginalName();
            $identity_back->move('uploads/provider', $identity_back_new_name);

            $Provider->identity_back = 'uploads/provider/' . $identity_back_new_name;
            $Provider->save();
        }

        if ($request->hasFile('criminal_feat')) {
            $criminal_feat = $request->criminal_feat;
            $criminal_feat_new_name = time() . $criminal_feat->getClientOriginalName();
            $criminal_feat->move('uploads/provider', $criminal_feat_new_name);

            $Provider->criminal_feat = 'uploads/provider/' . $criminal_feat_new_name;
            $Provider->save();
        }
        if ($request->hasFile('drug_analysis_licence')) {
            $drug_analysis_licence = $request->drug_analysis_licence;
            $drug_analysis_licence_new_name = time() . $drug_analysis_licence->getClientOriginalName();
            $drug_analysis_licence->move('uploads/provider', $drug_analysis_licence_new_name);

            $Provider->drug_analysis_licence = 'uploads/provider/' . $drug_analysis_licence_new_name;
            $Provider->save();
        }

        flash()->success(trans('admin.editMessageSuccess'));
        return redirect()->back()->with('flash_success', trans('admin.editMessageSuccess'));
    }

    public function cars()
    {
        return view('provider.myCar');
    }

    public function showCar($id)
    {
        $car = Car::find($id);
        return view('provider.showMyCar', compact('car'));
    }


    public function editCar($id)
    {
        $car = Car::find($id);
        return view('provider.upload.editCar', compact('car'));
    }

    public function updateCar(Request $request, $id)
    {
        $this->validate($request, [
            'car_front' => 'nullable|image',
            'car_back' => 'nullable|image',
            'car_left' => 'nullable|image',
            'car_right' => 'nullable|image',
            'car_licence_front' => 'nullable|image',
            'car_licence_back' => 'nullable|image',
        ]);

        $cars = Car::find($id);

        if ($request->hasFile('car_front')) {
            $car_front = $request->car_front;
            $car_front_new_name = time() . $car_front->getClientOriginalName();
            $car_front->move('uploads/car', $car_front_new_name);

            $cars->car_front = 'uploads/car/' . $car_front_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_back')) {
            $car_back = $request->car_back;
            $car_back_new_name = time() . $car_back->getClientOriginalName();
            $car_back->move('uploads/car', $car_back_new_name);

            $cars->car_back = 'uploads/car/' . $car_back_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_left')) {
            $car_left = $request->car_left;
            $car_left_new_name = time() . $car_left->getClientOriginalName();
            $car_left->move('uploads/car', $car_left_new_name);

            $cars->car_left = 'uploads/car/' . $car_left_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_right')) {
            $car_right = $request->car_right;
            $car_right_new_name = time() . $car_right->getClientOriginalName();
            $car_right->move('uploads/car', $car_right_new_name);

            $cars->car_right = 'uploads/car/' . $car_right_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_licence_front')) {
            $car_licence_front = $request->car_licence_front;
            $car_licence_front_new_name = time() . $car_licence_front->getClientOriginalName();
            $car_licence_front->move('uploads/car', $car_licence_front_new_name);

            $cars->car_licence_front = 'uploads/car/' . $car_licence_front_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_licence_back')) {
            $car_licence_back = $request->car_licence_back;
            $car_licence_back_new_name = time() . $car_licence_back->getClientOriginalName();
            $car_licence_back->move('uploads/car', $car_licence_back_new_name);

            $cars->car_licence_back = 'uploads/car/' . $car_licence_back_new_name;
            $cars->save();
        }
        flash()->success(trans('admin.editMessageSuccess'));
        return redirect()->back()->with('flash_success', trans('admin.editMessageSuccess'));
    }
}
