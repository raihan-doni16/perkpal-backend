<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJournalPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:journal_posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360',
            'author_name' => 'nullable|string|max:255',
            'author_avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360',
            'is_published' => 'boolean',
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
}
