<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\Tenant\TenantUnique;

use Illuminate\Foundation\Http\FormRequest;

class CursoRequest extends FormRequest
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
        // pega o ID da rota (ex: cursos/{id}/edit)
        $curso = $this->route('curso'); // já é Model
        $cursoId = $curso?->id;

        return [
            'name' => [
                'required',
                'max:155',
                new TenantUnique('courses', $cursoId),
            ],
        ];
    }

    /**
     * Get the custom messages for the validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('validation.attributes.name')]),
     ];
    }
}