<?php

namespace App\Http\Controllers\Resource;

use App\Fleet;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Setting;
use App\Revenue;
use App\M_revenue;
use App\Y_revenue;
use App\UserRequestPayment;

class RevenuesResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //dd("Alo");
        $revenues = UserRequestPayment::select()->get();
        $revenuesR = UserRequestPayment::where('commision','!=','0')->get();
        $revenuesSub = UserRequestPayment::where('commision','=','0')->get();
        $revenuesMonthly = M_revenue::select()->get()->sum('revenues_sub');

        return view('admin.revenuescompany.index', compact('revenues','revenuesR','revenuesSub',"revenuesMonthly"));
        //dd("Aloo");
        //return "Aloo";
    }
    // view Revenues monthly

    public function monthly()
    {

        $revenuesMonthly = M_revenue::select()->get();

        return view('admin.revenuescompany.Monthly', compact('revenuesMonthly'));
    }
    // view Revenues yearly
    public function yearly()
    {

        $revenuesYearly = Y_revenue::select()->get();

        return view('admin.revenuescompany.years', compact('revenuesYearly'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd("Aloo");
        return view('admin.fleet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'company' => 'required|max:255',
            'email' => 'required|unique:fleets,email|email|max:255',
            'mobile' => 'required|numeric|min:11|max:11|regex:/(01)[0-9]{9}/',
            'number_tax_card' => 'required|numeric',
            'logo' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'commercial_register' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'tax_card' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'password' => 'required|min:6|confirmed',
        ]);

        try{

            $fleet = $request->all();
            $fleet['password'] = bcrypt($request->password);
            $fleet['id_url'] = str_random(100);


            $fleet = Fleet::create($fleet);

            // $picture = $request->picture;
            // $code = rand(111111111, 999999999);
            // $picture_new_name = time() . $code."pp";
            // $picture->move('uploads/user', $picture_new_name);

            // $user->picture = 'uploads/user/' . $picture_new_name;
            // $user->save();

            if ($request->hasFile('logo')) {
                $logo = $request->logo;
                $code = rand(111111111, 999999999);
                $logo_new_name = time() . $code ."l";
                $logo->move('uploads/fleet', $logo_new_name);

                $fleet->logo = 'uploads/fleet/' . $logo_new_name;
                $fleet->save();
            }

            if ($request->hasFile('tax_card')) {
                $tax_card = $request->tax_card;
                $code = rand(111111111, 999999999);
                $tax_card_new_name = time() . $code ."tc";
                $tax_card->move('uploads/fleet', $tax_card_new_name);

                $fleet->tax_card = 'uploads/fleet/' . $tax_card_new_name;
                $fleet->save();
            }

            if ($request->hasFile('commercial_register')) {
                $commercial_register = $request->commercial_register;
                $code = rand(111111111, 999999999);
                $commercial_register_new_name = time() . $code."cr";
                $commercial_register->move('uploads/fleet', $commercial_register_new_name);

                $fleet->commercial_register = 'uploads/fleet/' . $commercial_register_new_name;
                $fleet->save();
            }

            return back()->with('flash_success',trans('admin.createMessageSuccess'));

        }

        catch (Exception $e) {
            return back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $fleet = Fleet::findOrFail($id);
            return view('admin.fleet.edit',compact('fleet'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', trans('admin.Disabled for demo purposes! Please contact us at info@appoets.com'));
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'company' => 'required|max:255',
            'mobile' => 'required|numeric|min:11|max:11|regex:/(01)[0-9]{9}/',
            'number_tax_card' => 'required|numeric',
            'logo' => 'mimes:jpeg,jpg,bmp,png',
            'commercial_register' => 'mimes:jpeg,jpg,bmp,png',
            'tax_card' => 'mimes:jpeg,jpg,bmp,png',
        ]);

        try {

            $fleet = Fleet::findOrFail($id);

            $fleet->update($request->except('password'));

            if ($request->hasFile('logo')) {
                $logo = $request->logo;
                $code = rand(111111111, 999999999);
                $logo_new_name = time() . $code ."l";
                $logo->move('uploads/fleet', $logo_new_name);

                $fleet->logo = 'uploads/fleet/' . $logo_new_name;
                $fleet->save();
            }

            if ($request->hasFile('tax_card')) {
                $tax_card = $request->tax_card;
                $code = rand(111111111, 999999999);
                $tax_card_new_name = time() . $code ."tc";
                $tax_card->move('uploads/fleet', $tax_card_new_name);

                $fleet->tax_card = 'uploads/fleet/' . $tax_card_new_name;
                $fleet->save();
            }

            if ($request->hasFile('commercial_register')) {
                $commercial_register = $request->commercial_register;
                $code = rand(111111111, 999999999);
                $commercial_register_new_name = time() . $code ."cr";
                $commercial_register->move('uploads/fleet', $commercial_register_new_name);

                $fleet->commercial_register = 'uploads/fleet/' . $commercial_register_new_name;
                $fleet->save();
            }
//            dd($fleet);

            return redirect()->route('admin.fleet.index')->with('flash_success', trans('admin.editMessageSuccess'));
        }

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fleet  $Fleet
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', trans('admin.Disabled for demo purposes! Please contact us at info@appoets.com'));
        }

        try {
           $fleet = Fleet::find($id);
            foreach ($fleet->providers as $item) {
                $item->delete();
           }

            foreach ($fleet->cars as $item) {
                $item->delete();
           }
           $fleet->delete();
            return back()->with('message', trans('admin.deleteMessageSuccess'));
        }
        catch (Exception $e) {
            return back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }

    public function wallet_update(Request $request)
    {
        try {
            $fleet = Fleet::where(['id' => $request->user_id])->first();
//            dd($request->user_id);
            $total_cash = $fleet->wallet_balance + $request->cash;
            $fleet->wallet_balance = $total_cash;
            $fleet->save();
            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    public function trashed()
    {
        $fleet = Fleet::onlyTrashed()->get();
        return view('admin.fleet.softDeleted')->with('fleets', $fleet);
    }

    public function hdelete($id)
    {
        $worker = Fleet::withTrashed()->where('id', $id)->first();
        $worker->forceDelete();
        return redirect()->back();
    }

    public function restore($id)
    {
        $worker = Fleet::withTrashed()->where('id', $id)->first();
        $worker->restore();
        return redirect()->route('admin.fleet.index');
    }
}
