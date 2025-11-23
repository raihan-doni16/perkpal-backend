<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $cover = $this->cover_image;
        $coverUrl = null;
        if ($cover) {
            if (preg_match('/^https?:\/\//i', $cover)) {
                $coverUrl = url('/api/v1/media/proxy?url=' . urlencode($cover));
            } else {
                $coverUrl = asset('storage/' . $cover);
            }
        }

        $avatar = $this->author_avatar ?? null;
        $avatarUrl = null;
        if ($avatar) {
            if (preg_match('/^https?:\/\//i', $avatar)) {
                $avatarUrl = url('/api/v1/media/proxy?url=' . urlencode($avatar));
            } else {
                $avatarUrl = asset('storage/' . $avatar);
            }
        }

        $og = $this->og_image ?? null;
        $ogUrl = null;
        if ($og) {
            if (preg_match('/^https?:\/\//i', $og)) {
                $ogUrl = url('/api/v1/media/proxy?url=' . urlencode($og));
            } else {
                $ogUrl = asset('storage/' . $og);
            }
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'cover_image' => $coverUrl,
            // compatibility with frontend expecting og_image
            'og_image' => $ogUrl ?? $coverUrl,
            'category' => $this->category?->name,
            'category_id' => $this->category_id,
            'category_slug' => $this->category?->slug,
            'category_data' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null,
            'tags' => $this->tags,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'reading_time' => method_exists($this->resource, 'getReadingTime') ? $this->getReadingTime() : null,
            'author' => [
                'name' => $this->author_name ?? null,
                'avatar' => $avatarUrl,
            ],
            'author_name' => $this->author_name ?? null,
            'author_avatar' => $avatarUrl,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'og_title' => $this->og_title ?? $this->meta_title ?? $this->title,
            'og_description' => $this->og_description ?? $this->meta_description ?? $this->excerpt,
            'twitter_title' => $this->twitter_title ?? $this->meta_title ?? $this->title,
            'twitter_description' => $this->twitter_description ?? $this->meta_description ?? $this->excerpt,
        ];
    }
}
