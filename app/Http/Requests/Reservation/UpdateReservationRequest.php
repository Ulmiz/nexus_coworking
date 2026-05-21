<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para actualizar una reserva existente
 */
class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'start_time' => $this->start_time ? str_replace('T', ' ', $this->start_time) : null,
            'end_time' => $this->end_time ? str_replace('T', ' ', $this->end_time) : null,
        ]);
    }

    public function rules(): array
    {
        $reservationId = $this->route('reservation')->id;

        return [
            'room_id' => [
                'sometimes',
                'required',
                'exists:rooms,id',
                'integer',
            ],
            'start_time' => [
                'sometimes',
                'required',
                'date_format:Y-m-d H:i',
                'after:now',
            ],
            'end_time' => [
                'sometimes',
                'required',
                'date_format:Y-m-d H:i',
                'after:start_time',
            ],
            'status' => [
                'sometimes',
                'required',
                'in:pending,confirmed,cancelled',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'Debes seleccionar una sala.',
            'room_id.exists' => 'La sala seleccionada no es válida.',
            'start_time.after' => 'La hora de inicio debe ser posterior a la fecha actual.',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'status.in' => 'El estado debe ser: pending, confirmed o cancelled.',
        ];
    }
}
