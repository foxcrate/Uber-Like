<?php

namespace App\Http\Controllers\Resource;

use App\User;
use App\Promocode;
use App\UserRequests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PromocodeResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $promocodes = Promocode::orderBy('created_at', 'desc')->get();
        foreach ($promocodes as $promo) {
            if (date("Y-m-d") > $promo->expiration) {
                if ($promo->status == 'ADDED') {
                    $promo->status = 'EXPIRED';
                    $promo->save();
                }
            }
        }
        return view('admin.promocode.index', compact('promocodes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {

//        $trip = UserRequests::all();

        $fullName = DB::table("users")
//           ->join('user_requests', 'users.id', '=', $trip[0]['user_id'])
            ->select("id", DB::raw("CONCAT(first_name, ' ', last_name, ' ', mobile, ' ', total_trips, ' ', total_price) as name "))
            ->where('total_trips', '>=', 0)
            ->where('total_price', '>=', 0)
            ->pluck("name", "id");
        //$fullName=User::select('first_name','last_name','mobile')->get();
        // foreach($user in $fullName){
        //     full
        // }
        
        //return $fullName[3];

        return view('admin.promocode.create', compact('fullName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'promo_code' => 'required|max:191|unique:promocodes',
            'discount' => 'required|numeric',
            'expiration' => 'required',
            'users_list' => 'required',
        ]);

        try {
            $record = Promocode::create($request->except('users_list'));
            //$record->discount=100*($request->discount);
            //$record->save();
            //return $record;
            $record->users()->attach($request->users_list);
//            Promocode::create($request->all());
            return back()->with('flash_success', trans('admin.createMessageSuccess'));

        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Promocode $promocode
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return Promocode::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Promocode $promocode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $promocode = Promocode::findOrFail($id);
            $trip = UserRequests::all();
            $fullName = DB::table("users")
                ->select("id", DB::raw("CONCAT(first_name, ' ', last_name, ' ', mobile, ' ', total_trips, ' ', total_price) as name "))
                ->where('total_trips', '>', 0)
                ->where('total_price', '>', 1000)
                ->pluck("name", "id")->toArray();
//            $user_mobile=DB::table('users')->get();
//            $user_payament=DB::table('user_request_payments')->get();
            return view('admin.promocode.edit', compact('promocode', 'fullName'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

//    public function edit($id)
//    {
//        $model = Promocode::findOrFail($id);
//        return view('admin.promocode.edit', compact('model'));
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Promocode $promocode
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'promo_code' => 'required|max:191|unique:promocodes',
            'discount' => 'required|numeric',
            'expiration' => 'required',
//            'user_mobile' => 'required',
        ]);

        try {

            $promo = Promocode::findOrFail($id);

            $promo->promo_code = $request->promo_code;
            $promo->discount = $request->discount;
            $promo->expiration = $request->expiration;
//            $promo->user_mobile = $request->user_mobile;
            $promo->users()->sync((array)$request->input('users_list'));
            $promo->save();

            return redirect()->route('admin.promocode.index')->with('flash_success', trans('admin.editMessageSuccess'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Promocode $promocode
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Promocode::find($id)->delete();
            return back()->with('message', trans('admin.deleteMessageSuccess'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }
}
