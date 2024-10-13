<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskStatusEnum;
use App\Rules\DateTimeGreaterThan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'started_at' => 'nullable|date_format:Y-m-d H:i:s',
            'finished_at' => [
                'nullable',
                'date_format:Y-m-d H:i:s',
                new DateTimeGreaterThan('started_at'),
            ],
            'status' => [
                'nullable',
                'string',
                Rule::enum(TaskStatusEnum::class),
            ],
            'tags' => 'nullable|list',
            'tags.*' => 'required|string',
        ];
    }
}
