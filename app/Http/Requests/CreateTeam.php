<?php

namespace KIPR\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeam extends FormRequest
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
            'name' => 'bail|required|String',
            'email' => 'bail|required|String',
            'code' => 'bail|required|String|unique:teams,code'
        ];
    }
}
