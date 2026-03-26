<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'in:abuse,advertising,spam,adult,other'],
            'custom_reason' => ['nullable', 'string', 'max:500', 'required_if:reason,other'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => '신고 사유를 선택해주세요.',
            'reason.in' => '올바른 신고 사유를 선택해주세요.',
            'custom_reason.required_if' => '기타 사유를 입력해주세요.',
            'custom_reason.max' => '기타 사유는 500자 이하로 입력해주세요.',
        ];
    }

}
