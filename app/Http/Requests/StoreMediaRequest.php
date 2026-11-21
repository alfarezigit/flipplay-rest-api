<?php

namespace App\Http\Requests;

use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>|string>
     */
    public function rules(): array
    {
        $isPatch = $this->isMethod('PATCH');
        $media = $this->route('media');
        $mediaId = $media instanceof Media ? $media->id : null;
        $required = $isPatch ? ['sometimes', 'required'] : ['required'];

        return [
            'title' => [
                ...$required,
                'string',
                'max:255',
                Rule::unique('media', 'title')->ignore($mediaId),
            ],
            'type' => [...$required, 'string', 'max:255'],
            'genre' => [...$required, 'string', 'max:255'],
            'release_year' => [...$required, 'integer'],
            'description' => [$isPatch ? 'sometimes' : 'nullable', 'nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul media wajib diisi.',
            'title.string' => 'Judul media harus berupa teks.',
            'title.max' => 'Judul media maksimal 255 karakter.',
            'title.unique' => 'Judul media sudah digunakan.',
            'type.required' => 'Jenis media wajib diisi.',
            'type.string' => 'Jenis media harus berupa teks.',
            'type.max' => 'Jenis media maksimal 255 karakter.',
            'genre.required' => 'Genre wajib diisi.',
            'genre.string' => 'Genre harus berupa teks.',
            'genre.max' => 'Genre maksimal 255 karakter.',
            'release_year.required' => 'Tahun rilis wajib diisi.',
            'release_year.integer' => 'Tahun rilis harus berupa angka bulat.',
            'description.string' => 'Deskripsi harus berupa teks.',
        ];
    }
}
