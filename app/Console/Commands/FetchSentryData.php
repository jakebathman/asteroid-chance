<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FetchSentryData extends Command
{
    protected $signature = 'fetch-sentry-data';

    protected $description = "Fetch data from NASA's Sentry API";

    public function handle()
    {
        $url = 'https://ssd-api.jpl.nasa.gov/sentry.api';

        $data = Http::withOptions(['verify' => false])->get($url)->json('data');

        $asteroid = collect($data)->keyBy('des')->get('2024 YR4');

        $impactProbability = $asteroid['ip'];
        $ipFormatted = number_format($impactProbability * 100, 2);

        $this->info("Impact probability: {$ipFormatted}%");

        Cache::put('probability', $ipFormatted);

    }
}
