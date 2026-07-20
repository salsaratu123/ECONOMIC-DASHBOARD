<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NewsService
{
    public function latest(string $topic = 'economy', int $limit = 6): array
    {
        return Cache::remember('news:' . md5($topic . $limit), now()->addMinutes(15), function () use ($topic, $limit) {
        $key = config('services.gnews.key');

        if (!$key) {
            return $this->fallback($topic);
        }

        try {
            $response = Http::timeout(15)->get('https://gnews.io/api/v4/search', [
                'q' => $topic,
                'lang' => 'en',
                'max' => $limit,
                'apikey' => $key,
            ]);
        } catch (\Throwable) {
            return $this->fallback($topic);
        }

        if (!$response->successful()) {
            return $this->fallback($topic);
        }

        return collect($response->json('articles', []))->map(fn ($article) => [
            'title' => $article['title'] ?? 'Untitled',
            'description' => $article['description'] ?? '',
            'image' => $article['image'] ?? '',
            'source' => $article['source']['name'] ?? 'GNews',
            'published_at' => $article['publishedAt'] ?? null,
            'url' => $article['url'] ?? '#',
            'topic' => $topic,
        ])->values()->all();
        });
    }

    public function grouped(): array
    {
        return [
            'economy' => $this->latest('economy'),
            'trade' => $this->latest('global trade supply chain'),
            'geopolitic' => $this->latest('geopolitics shipping trade'),
        ];
    }

    private function fallback(string $topic): array
    {
        return [[
            'title' => ucfirst($topic) . ' intelligence update',
            'description' => 'Connect a GNEWS_API_KEY to display live news from GNews.',
            'image' => '',
            'source' => 'Local fallback',
            'published_at' => now()->toIso8601String(),
            'url' => '#',
            'topic' => $topic,
        ]];
    }
}
