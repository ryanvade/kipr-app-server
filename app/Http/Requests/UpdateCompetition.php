<?php

namespace KIPR\Http\Requests;

use KIPR\Competition;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompetition extends FormRequest
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
          'name' => [
            'bail',
            'required',
            'String',
            Rule::unique('competitions')->ignore(request()->competition->id),
        ],
        'location' => 'bail|required|String',
        'startDate' => 'bail|required|date',
        'endDate' => 'bail|required|date'
      ];
    }
}
