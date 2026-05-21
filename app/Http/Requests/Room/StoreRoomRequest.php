<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para crear una nueva sala
 */
class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:rooms,name',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:500',
            ],
            'price_per_hour' => [
                'required',
                'numeric',
                'min:0',
                'max:10000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la sala es obligatorio.',
            'name.unique' => 'Ya existe una sala con ese nombre.',
            'capacity.required' => 'La capacidad es obligatoria.',
            'capacity.min' => 'La capacidad mínima es 1 persona.',
            'price_per_hour.required' => 'El precio por hora es obligatorio.',
            'price_per_hour.min' => 'El precio no puede ser negativo.',
        ];
    }
}
