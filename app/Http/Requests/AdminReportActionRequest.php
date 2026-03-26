<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminReportActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', 'string', 'in:resolved,rejected'],
            'content_action' => ['nullable', 'string', 'in:none,hide,delete'],
            'sanction_type' => ['nullable', 'string', 'in:none,warning,suspension_3d,suspension_7d,suspension_30d,ban'],
            'resolved_reason' => ['nullable', 'string', 'max:1000', 'required_if:decision,resolved'],
            'rejected_reason' => ['nullable', 'string', 'max:1000', 'required_if:decision,rejected'],
        ];
    }

    public function messages(): array
    {
        return [
            'decision.required' => '처리 방향을 선택해주세요.',
            'decision.in' => '올바른 처리 방향을 선택해주세요.',
            'content_action.in' => '올바른 게시글 처리 방식을 선택해주세요.',
            'sanction_type.in' => '올바른 사용자 제재 방식을 선택해주세요.',
            'resolved_reason.required_if' => '처리 메모를 입력해주세요.',
            'resolved_reason.max' => '처리 메모는 1000자 이하로 입력해주세요.',
            'rejected_reason.required_if' => '반려 사유를 입력해주세요.',
            'rejected_reason.max' => '반려 사유는 1000자 이하로 입력해주세요.',
        ];
    }
}
