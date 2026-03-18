<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation() : void
    {
        $this->merge([
            'title' => trim((string) $this->input('title')),
            'content' => trim((string) $this->input('content')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '제목을 입력해주세요.',
            'title.max' => '제목은 255자 이하로 입력해주세요.',
            'content.required' => '본문을 입력해주세요.',
        ];
    }
}
