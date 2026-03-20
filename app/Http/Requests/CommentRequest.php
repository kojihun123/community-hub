<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function commentField(): string
    {
        if ($this->has('edit_content')) {
            return 'edit_content';
        }

        if ($this->has('reply_content')) {
            return 'reply_content';
        }

        return 'comment_content';
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            $this->commentField() => trim((string) $this->input($this->commentField())),
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
            $this->commentField() => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            $this->commentField() . '.required' => '댓글을 입력해주세요.',
            $this->commentField() . '.max' => '댓글은 1000자 이하로 입력해주세요.',
        ];
    }
}
