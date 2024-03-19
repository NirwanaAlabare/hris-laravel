<?php

namespace App\Http\Requests\Admin\Admin;

use App\Http\Requests\CoreRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends CoreRequest
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
        $rules['email']   = [
            'required',
            Rule::unique('admins')->ignore($this->route('admin'), 'id'),
        ];
        $rules['password']   = 'nullable|min:6';
        return $rules;
        
    }
}
