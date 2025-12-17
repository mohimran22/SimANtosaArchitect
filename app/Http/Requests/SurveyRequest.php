<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|uuid|exists:projects,id',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'site_area' => 'nullable|string|max:255',
            'building_area' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'survey_date' => 'required|string',
            'survey_time' => 'required',

            'employee_id'    => 'required|array',
            'employee_id.*'  => 'uuid',

            // Items dinamis
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:2000',
            'items.*.remark'      => 'nullable|string|max:2000',

            // 🔥 VALIDASI FOTO DOKUMENTASI (boleh banyak)
            'documentation'   => 'nullable|array',
            'documentation.*' => 'image|max:2048', // 5MB

            // 🔥 VALIDASI HASIL SURVEI (boleh banyak)
            'result_images'   => 'nullable|array',
            'result_images.*' => 'image|max:2048', // 5MB
        ];
    }
}
