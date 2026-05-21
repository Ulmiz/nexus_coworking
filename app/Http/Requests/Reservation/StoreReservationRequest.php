<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validación para crear una nueva reserva
 * Incluye validaciones de fecha, sala y disponibilidad
 */
class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'start_time' => str_replace('T', ' ', $this->start_time),
            'end_time' => str_replace('T', ' ', $this->end_time),
        ]);
    }

    public function rules(): array
    {
        $rules = [
            'room_id' => [
                'required',
                'exists:rooms,id',
                'integer',
            ],
            'start_time' => [
                'required',
                'date_format:Y-m-d H:i',
                'after:now',
            ],
            'end_time' => [
                'required',
                'date_format:Y-m-d H:i',
                'after:start_time',
            ],
        ];

        if ($this->user()?->isAdmin()) {
            $rules['user_id'] = [
                'required',
                'integer',
                Rule::exists('users', 'id')->whereNull('deleted_at'),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'Debes seleccionar una sala.',
            'room_id.exists' => 'La sala seleccionada no es válida.',
            'start_time.required' => 'La fecha y hora de inicio son obligatorias.',
            'start_time.date_format' => 'El formato debe ser: YYYY-MM-DD HH:MM',
            'start_time.after' => 'La hora de inicio debe ser posterior a la fecha y hora actual.',
            'end_time.required' => 'La fecha y hora de fin son obligatorias.',
            'end_time.date_format' => 'El formato debe ser: YYYY-MM-DD HH:MM',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'user_id.required' => 'Debes seleccionar un usuario.',
            'user_id.exists' => 'El usuario seleccionado no es válido.',
        ];
    }
}
