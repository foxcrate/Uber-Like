<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'id_borr'  => 'required|exists:users,id',
            'id_models'  => 'required|exists:car_models,id',
            'id_governorate'  => 'required|exists:governorates,id',
            'gearbox' =>'required|numeric',
            'number_seats' =>'required|numeric',
            'full_type' =>'required|numeric',
            'color' =>'required',
            'address'   => 'required',
            'note_ar'   => 'required|min:4',
            'note_en'   => 'required|min:4',
            'price'   => 'required',
        ];
    }
    public function messages()
    {
        return [
            'id_borr.required'  => __('validation.required'),
            'id_borr.exists'  => __('validation.exists'),
            'id_models.exists'  => __('validation.exists'),
            'id_models.required'  => __('validation.required'),
            'id_governorate.exists'  => __('validation.exists'),
            'id_governorate.required'  => __('validation.required'),
            'gearbox.required'  => __('validation.required'),
            'gearbox.numeric'  => __('validation.numeric'),
            'number_seats.required'  => __('validation.required'),
            'number_seats.numeric'  => __('validation.numeric'),
            'full_type.required'  => __('validation.required'),
            'full_type.numeric'  => __('validation.numeric'),
            'color.required'  => __('validation.required'),
            'address.required'  => __('validation.required'),
            'note_ar.required'  => __('validation.required'),
            'note_ar.min'  => __('validation.min.string'),
            'note_en.required'  => __('validation.required'),
            'note_en.min'  => __('validation.min.string'),
            'price.required'  => __('validation.required'),

        ];
    }
}
