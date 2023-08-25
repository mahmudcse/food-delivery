<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('product.create');
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'price' => (int) ($this->price * 100),
        ]);
    }
}
