<?php

namespace App\Http\Requests\Web\Area;

use Illuminate\Foundation\Http\FormRequest;

class AreaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): string
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
        return [
            'ref' => ['required', 'string', 'alpha_dash', 'min:1', 'max:255', 'unique:areas,ref,' . $this->area?->id],
            'name' => ['required', 'string', 'min:1', 'max:255'],
        ];
    }
}
