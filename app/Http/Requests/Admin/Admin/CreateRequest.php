<?php
namespace App\Http\Requests\Admin\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Admin\Employee
 */
class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        // If admin
        $admin = admin();
        return $admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name'      => 'required',
            'email'         => 'required|email|unique:admins',
            'password'      => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required'
        ];
    }
}
