<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NewsService
{
    public function latest(string $topic = 'economy', int $limit = 6): array
    {
        return Cache::remember('news:' . md5($topic . $limit), now()->addMinutes(15), function () use ($topic, $limit) {
            $key = Setting::get('gnews_api_key', config('services.gnews.key'));

            if (!$key) {
                $this->logActivity('GNews Key Empty / Not Configured', 401);
                return $this->fallback($topic);
            }

            try {
                $response = Http::timeout(15)->get('https://gnews.io/api/v4/search', [
                    'q' => $topic,
                    'lang' => 'en',
                    'max' => $limit,
                    'apikey' => $key,
                ]);

                if ($response->successful()) {
                    $this->logActivity('Articles Lexicon Parsing Success', 200);
                    return collect($response->json('articles', []))->map(fn ($article) => [
                        'title' => $article['title'] ?? 'Untitled',
                        'description' => $article['description'] ?? '',
                        'image' => $article['image'] ?? '',
                        'source' => $article['source']['name'] ?? 'GNews',
                        'published_at' => $article['publishedAt'] ?? null,
                        'url' => $article['url'] ?? '#',
                        'topic' => $topic,
                    ])->values()->all();
                }

                $this->logActivity('GNews API Key Invalid / Rejected', $response->status());

            } catch (\Throwable $e) {
                $this->logActivity('GNews Connection Timeout', 500);
            }

            return $this->fallback($topic);
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

    private function logActivity(string $status, int $code = 200)
    {
        try {
            ApiLog::create([
                'target_service' => 'GNews Sentiment',
                'status_request' => $status,
                'response_code' => $code,
            ]);
        } catch (\Throwable $e) {}
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