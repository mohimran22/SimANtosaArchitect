<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAccountingJournalRequest extends FormRequest
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
            'license_id' => [
                'required',
                'exists:licenses,id',
                function ($attribute, $value, $fail) {
                    $user = Auth::user();

                    if ($user->hasRole('Akuntan')) {
                        $licenses = $user->employee?->licenses;

                        if (!$licenses || $licenses->count() === 0) {
                            return $fail('Lisensi tidak ditemukan untuk user ini.');
                        }

                        if (!$licenses->pluck('id')->contains($value)) {
                            return $fail('Lisensi tidak valid untuk user ini.');
                        }
                    }
                },
            ],
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
            'details' => 'required|array',
            'details.*.account_id' => 'required|uuid',
            'details.*.debit' => 'nullable|numeric',
            'details.*.credit' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'license_id.required' => 'Lisensi wajib dipilih.',
            'license_id.exists' => 'Lisensi tidak valid.',
            'details.required' => 'Detail jurnal wajib diisi.',
        ];
    }
}
