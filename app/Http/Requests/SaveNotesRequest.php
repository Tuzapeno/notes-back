<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveNotesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'notes' => 'required|array',
            'notes.*.id' => 'required|integer',
            'notes.*.title' => 'nullable|string|max:255',
            'notes.*.description' => 'nullable|string|max:1000',
            'notes.*.date' => 'required|date',
            'notes.*.initialColor' => 'string',
            'notes.*.lastEditDate' => 'date',
        ];
    }
}
