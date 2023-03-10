<?php

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRquest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|min:3|max:255|unique:users,email,'. $this->request->get('user_id') .'',
            'mobile' => 'required|digits:11|unique:users,mobile,'. $this->request->get('user_id') .'',
            'role' => 'required|in:user,admin,seller',
        ];
    }
}
