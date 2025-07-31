<?php

namespace App\Http\Requests;

class ValidateAnnouncementRequest extends BaseRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // "user_id" => "required|numeric|exists:users,id",
            'topic' => 'required|unique:announcements|string|min:4|max:255',
            'message' => 'required|string|min:4',
            'date_start' => 'required|date_format:Y-m-d|before_or_equal:date_end',
            'date_end' => 'required|date_format:Y-m-d|after_or_equal:date_start',
        ];
    }
}
