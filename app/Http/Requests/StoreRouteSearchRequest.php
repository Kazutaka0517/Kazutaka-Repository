<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteSearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start_point' => 'required|string|max:255',
            'end_point' => 'required|string|max:255',
            'start_time' => 'required|date',
            'via_points' => 'nullable|string',
        ];
    }
}