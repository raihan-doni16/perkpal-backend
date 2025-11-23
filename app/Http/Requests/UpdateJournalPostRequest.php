<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJournalPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $postId = $this->route('journal') ?? $this->route('id');
        return [
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('journal_posts', 'slug')->ignore($postId)],
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360',
            'author_name' => 'nullable|string|max:255',
            'author_avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        // Handle checkbox value - if not present in request, set to false (0)
        if (!$this->has('is_published')) {
            $this->merge(['is_published' => 0]);
        }

        // Handle empty category_id
        if ($this->has('category_id') && $this->input('category_id') === '') {
            $this->merge(['category_id' => null]);
        }
    }
}
