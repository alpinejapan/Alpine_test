<?php

namespace Modules\Commercial\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommercialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            $rules = [
                'category'=>'required',
                'title'=>'required',
                'maker'=>'required',
                'year_of_registration'=>'required',
                'model'=>'required',
                'chassis_number'=>'required',
                'year_of_made'=>'required',
                'kilometers'=>'required',
                'engine_type'=>'required',
                'fuel'=>'required',
                'price_dollar'=>'required',
                'sell_points'=>'required',
                'remarks'=>'required',
                'active'=>'required',  
            ];
        }

        if ($this->isMethod('put')) {
            $rules = [
                'category'=>'required',
                'title'=>'required',
                'maker'=>'required',
                'year_of_registration'=>'required',
                'model'=>'required',
                'chassis_number'=>'required',
                'year_of_made'=>'required',
                'kilometers'=>'required',
                'engine_type'=>'required',
                'fuel'=>'required',
                'price_dollar'=>'required',
                'sell_points'=>'required',
                'remarks'=>'required',
                'active'=>'required',
            ];
        }
        return $rules;
    }
}
