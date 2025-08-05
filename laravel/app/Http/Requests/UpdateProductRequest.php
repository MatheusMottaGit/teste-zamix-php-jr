<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sale_price' => 'required|numeric',
            'cost_price' => 'nullable|numeric',
            'type' => 'required|string|in:simple,compound',
            'components' => 'nullable|array',
            'components.*.quantity' => 'required|numeric|min:1',
            'components.*.id' => 'required|exists:products,id',
        ];
    }
}
