<?php

namespace App\Http\Controllers\Resource;

use App\Car;
use App\CarModel;
use App\CarProvider;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProviderResources\ProfileController;
use App\Itinerary;
use App\Mail\EilbazProviderNotImage;
use App\Provider;
use App\ProviderService;
use App\Station;
use App\UserRequests;
use Illuminate\Contracts\View\Factory as FactoryAlias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return FactoryAlias|\Illuminate\View\View
     */
    public function index()
    {
        $records = Itinerary::orderBy('created_at', 'desc')->get();
        return view('admin.itineraries.index', compact('records'));
    }


    public function cars(Request $request)
    {
        if ($request->provider_id != 0) {
            $provider_car = CarProvider::where('provider_id', $request->provider_id)->get();
            $provider = Provider::where('id', $request->provider_id)->first();
//        $provider->cars()->attach($request->user_list);
//        $cars = Car::where('id' , $provider_car->car_id)->get();
//        $cars = Car::where(function ($query) use ($request) {
//            if ($request->has('provider_id')) {
//                $query->where('provider_id', $request->provider_id);
//            }
//        })->get();
            $response = [
                'status' => 1,
                'message' => 'تمت العمليه بنجاح',
                'data' => $provider->cars,
            ];
            return response()->json($response);
        } else {
            $response = [
                'status' => 0,
                'message' => 'لم تمت العمليه بنجاح',
                'data' => null,
            ];
            return response()->json($response);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return FactoryAlias|\Illuminate\View\View
     */
    public function create()
    {
        $day_trip_times = DB::table('day_trip_times')->get();
        $providers = DB::table('providers')->get();
        $cars = DB::table('cars')->get();
        $transportation_types = DB::table('transportation_types')->get();
        $station_names = DB::table("stations")
            ->select("id", DB::raw("CONCAT(station , ' ', substation, ' ') as name ")) // , ' ( ', substation, ' ) '
            ->pluck("name", "id")->toArray();
        return view('admin.itineraries.create', compact('day_trip_times', 'providers', 'cars', 'transportation_types', 'station_names'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        //
//        dd($request->all());
        $this->validate($request, [
            's_address_ar' => 'required',
            's_address_en' => 'nullable',
            's_latitude' => 'required',
            's_longitude' => 'required',
            'd_address_ar' => 'required',
            'd_address_en' => 'nullable',
            'd_latitude' => 'required',
            'd_longitude' => 'required',

            'from_time' => 'required',
            'to_time' => 'required',
            'day_trip_time_id' => 'required|exists:day_trip_times,id',
            'provider_id' => 'required|exists:providers,id',
            'car_id' => 'required|exists:cars,id',
            'transportation_type_id' => 'required|exists:transportation_types,id',
            'user_list' => 'nullable',
            'station_list' => 'nullable',
        ]);

        $cars = Itinerary::create($request->except('user_list'));
        $cars->users()->attach($request->user_list);
        $cars->stations()->attach($request->station_list);
        $cars->capacity = $cars->transportationType->capacity - count($request->user_list);
        $cars->number_station = count($request->station_list);
        $dist2 = ProfileController::distance_between($request->s_latitude, $request->s_longitude, $request->d_latitude, $request->d_longitude, 'K');
        $dist = (float)number_format((float)$dist2, 2, '.', '');
        $cars->distance = $dist;
        $cars->save();
        $providerService = ProviderService::where('provider_id', $request->provider_id)
            ->orderByDesc('created_at')->first();

        $car = Itinerary::findOrFail($cars->id);

        $booking_id = rand(00000000, 99999999);

        $trip = UserRequests::create([
            'itinerary_id' => $cars->id,
            'provider_id' => $request->provider_id,
            'current_provider_id' => $request->provider_id,
            'service_type_id' => $providerService->service_type_id,
            'booking_id' => $booking_id,
            's_address_ar' => $request->s_address_ar,
            's_latitude' => $request->s_latitude,
            's_longitude' => $request->s_longitude,
            'd_address_ar' => $request->d_address_ar,
            'd_latitude' => $request->d_latitude,
            'd_longitude' => $request->d_longitude,
            'status' => 'SCHEDULED',
            'schedule_at' => $cars->dayTripTime->day . ' ' . $car->from_time,
        ]);

        flash()->success(trans('admin.createMessageSuccess'));
        return redirect(route('admin.itinerary.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return FactoryAlias|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = Itinerary::findOrFail($id);
        $day_trip_times = DB::table('day_trip_times')->get();
        $providers = DB::table('providers')->get();
        $cars = DB::table('cars')->get();
        $station_names = DB::table("stations")
            ->select("id", DB::raw("CONCAT(station , ' ', substation, ' ') as name ")) // , ' ( ', substation, ' ) '
            ->pluck("name", "id")->toArray();
        $transportation_types = DB::table('transportation_types')->get();
        return view('admin.itineraries.edit', compact('model', 'day_trip_times', 'providers', 'cars', 'transportation_types', 'station_names'));
    }

//
    public
    function update(Request $request, $id)
    {
        $records = Itinerary::findOrFail($id);
        $this->validate($request, [
            's_address_ar' => 'required',
            's_latitude' => 'required',
            's_longitude' => 'required',
            'd_address_ar' => 'required',
            'd_latitude' => 'required',
            'd_longitude' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',

            'day_trip_time_id' => 'required|exists:day_trip_times,id',
            'provider_id' => 'required|exists:providers,id',
            'car_id' => 'required|exists:cars,id',
            'transportation_type_id' => 'required|exists:transportation_types,id',

            'user_list' => 'nullable',
            'station_list' => 'nullable',
        ]);
        $records->update($request->all());
        $records->users()->sync((array)$request->input('user_list'));
        $records->stations()->sync((array)$request->input('station_list'));
        $records->capacity = $records->transportationType->capacity - count($request->user_list);
        $records->number_station = count($request->station_list);
        $dist2 = ProfileController::distance_between($request->s_latitude, $request->s_longitude, $request->d_latitude, $request->d_longitude, 'K');
        $dist = (float)number_format((float)$dist2, 2, '.', '');
        $records->distance = $dist;
        $records->save();

        $record = Itinerary::findOrFail($records->id);
        $trip = UserRequests::where('itinerary_id', $id)->orderByDesc('created_at')->first();
        $trip->update([
            's_address_ar' => $request->s_address_ar,
            's_latitude' => $request->s_latitude,
            's_longitude' => $request->s_longitude,
            'd_address_ar' => $request->d_address_ar,
            'd_latitude' => $request->d_latitude,
            'd_longitude' => $request->d_longitude,
            'status' => 'SCHEDULED',
            'schedule_at' => $records->dayTripTime->day . ' ' . $record->from_time,
        ]);
        flash()->success(trans('admin.editMessageSuccess'));
        return redirect(route('admin.itinerary.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $record = Itinerary::findOrFail($id);
        $userTrip = $record->userRequests->first();
        if ($userTrip != null) {
            $userTrip->update([
                'status' => 'CANCELLED'
            ]);
            $userTrip->delete();
        }
        $record->delete();

        flash()->success(trans('admin.deleteMessageSuccess'));
        return back();
    }

    public function statusOpen($id)
    {
        $record = Itinerary::findOrFail($id);
        $record->update(['status' => '1']);
        flash()->success(trans('admin.editMessageSuccess'));
        return back();
    }

    public function statusClose($id)
    {
        $record = Itinerary::findOrFail($id);
        $record->update(['status' => '0']);
        flash()->success(trans('admin.editMessageSuccess'));
        return back();
    }
}
