<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validación para actualizar una sala existente
 */
class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $roomId = $this->route('room')->id;

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('rooms', 'name')->ignore($roomId)->whereNull('deleted_at'),
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'capacity' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                'max:500',
            ],
            'price_per_hour' => [
                'sometimes',
                'required',
                'numeric',
                'min:0',
                'max:10000',
            ],
        ];
    }
}
