<?php

namespace App\Http\Requests\Referral;

use Illuminate\Foundation\Http\FormRequest;

class ReferralRequest extends FormRequest
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
            'referrer_user_id' => 'required|exists:users,id',
            'referred_user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,completed,expired', // Add other valid statuses if needed
            'reward_type' => 'required',
            'reward_value' => 'required|numeric',
            'expiration_date' => 'nullable|date',
        ];
    }
}
