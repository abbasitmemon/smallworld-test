<?php

namespace App\Http\Requests\Api\Movie;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateMovieRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title'         => 'required',
            'episode_id'    => 'required',
            'opening_crawl' => 'required',
            'director'      => 'required',
            'producer'      => 'required',
            'release_date'  => 'required',
            'url'           => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(apiResponse(false, implode("\n", $validator->errors()->all()), [], 422));
    }
}
