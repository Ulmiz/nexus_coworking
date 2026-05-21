<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

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

    public function rules(): array
    {
        return [
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
        ];
    }
}
