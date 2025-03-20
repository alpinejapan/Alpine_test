<?php

namespace Modules\Categories\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriesRequest extends FormRequest
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
                'name'=>'required',
                'average_price'=>'required|numeric|min:1',
                'active'=>'accepted'
            ];
        }

        if ($this->isMethod('put')) {
            $rules = [
                'name'=>'required',
                'average_price'=>'required|numeric|min:1',
            ];
        }
        return $rules;
    }
}
