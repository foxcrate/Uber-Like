<?php

namespace App\Http\Controllers;

use App\User;

use App\CarModel;
use App\Governorate;
use App\Request_car;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    /************************ View Home ****************************/
    public function index(){
        //dd("Aloo");
        $users=User::all();
        //dd($users[0]);
        $pic=$users[0]->picture;
        return view('index',['pic'=>$pic]);
    }

    /************************ View About ****************************/
    public function viewAbout(){
        return view('about');
    }

     /************************ View About ****************************/
     public function viewCondition(){
        $page = 'privacy';
        $title = ' شروط الخدمة';
        return view('static2', compact('page', 'title'));
    }

    /************************ View privacy ****************************/
    public function viewprivacy(){
        $page = 'page_privacy';
    $title = 'سياسة الخصوصية';
    return view('static', compact('page', 'title'));
    }

    /************************ lang ****************************/
    public function lang($lang) {
        if (in_array($lang, ['ar', 'en'])) {
            if (auth()->user()) {
                $user = auth()->user();
                $user->lang = $lang;
                $user->save();
            } else {
                if (session()->has('lang')) {
                    session()->forget('lang');
                }
                session()->put('lang', $lang);
            }
        } else {
            if (auth()->user()) {
                $user = auth()->user();
                $user->lang = 'ar';
                $user->save();
            } else {
                if (session()->has('lang')) {
                    session()->forget('lang');
                }
                session()->put('lang', 'ar');
            }
        }
        return back();

    }
    /************************ lang ****************************/
    public function AdminLang($lang) {
        session()->has('lang') ? session()->forget('lang') : '';
        $lang == 'ar' ? session()->put('lang', 'ar') : session()->put('lang', 'en');
        return back();
    }

    /************************ cars ****************************/
    public function all_index()
    {
        $governorates = Governorate::select()->get();
        $models= CarModel::select()->get();
        $cars= Request_car::where('status',1)->orderBy('id', 'DESC')->paginate(10);
        return view('allCars',compact("cars",'governorates','models'));
    }

    /********************** Provinces *****************************/
    public function Provinces_index($id)
    {
        $governorates = Governorate::select()->get();
        $models= CarModel::select()->get();
        $cars= Request_car::where('status',1)->where('id_governorate',$id)->orderBy('id', 'DESC')->paginate(10);
        return view('allCars',compact("cars",'governorates','models'));
    }

    /********************** Provinces *****************************/
    public function Model_index($id)
    {
        $governorates = Governorate::select()->get();
        $models= CarModel::select()->get();
        $cars= Request_car::where('status',1)->where('id_models',$id)->orderBy('id', 'DESC')->paginate(10);
        return view('allCars',compact("cars",'governorates','models'));
    }

    /********************** year Model *****************************/
    public function year_index($id)
    {
        $governorates = Governorate::select()->get();
        $models= CarModel::select()->get();
        $cars= Request_car::where('status',1)->where('year_date',$id)->orderBy('id', 'DESC')->paginate(10);
        return view('allCars',compact("cars",'governorates','models'));
    }

    /********************** Full Type *****************************/
    public function Full_index($id)
    {
        $governorates = Governorate::select()->get();
        $models= CarModel::select()->get();
        $cars= Request_car::where('status',1)->where('full_type',$id)->orderBy('id', 'DESC')->paginate(10);
        return view('allCars',compact("cars",'governorates','models'));
    }


    /********************** Gearbox *****************************/
    public function Bax_index($id)
    {
        $governorates = Governorate::select()->get();
        $models= CarModel::select()->get();
        $cars= Request_car::where('status',1)->where('gearbox',$id)->orderBy('id', 'DESC')->paginate(10);
        return view('allCars',compact("cars",'governorates','models'));
    }

}
