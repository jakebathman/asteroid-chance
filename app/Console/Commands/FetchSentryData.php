<?php

namespace App\Console\Commands;

use App\Models\SpaceObject;
use App\Models\SpaceObjectDetail;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
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

        $allObjects = collect($data)->keyBy('des');

        $objectsForDetailFetch = [];

        foreach ($allObjects as $designation => $o) {
            $ip = $this->sciToFloat(Arr::get($o, 'ip', 0));
            $spaceObject = SpaceObject::updateOrCreate(
                ['designation' => $designation],
                [
                    'sentry_id' => $o['id'],
                    'fullname' => $o['fullname'],
                    'ip' => $ip,
                    'ts_max' => $o['ts_max'],
                ]
            );

            // If Impact Probability is greater than 0.01, we will fetch details
            if ($ip >= 0.00001) {
                $detail = Http::withOptions(['verify' => false])->get($url . '?des=' . $designation)->json();

                $impacts = collect(Arr::get($detail, 'data', []))->map(function ($impact) {
                    $impact['energy'] = $this->sciToFloat($impact['energy']);
                    $impact['ip'] = $this->sciToFloat($impact['ip']);
                    return $impact;
                });

                $impactSoonest = $impacts->sortBy('date')->first();
                $impactHighestIp = $impacts->sortBy('ip', descending: true)->first();

                SpaceObjectDetail::updateOrCreate(
                    ['space_object_id' => $spaceObject->id],
                    [
                        'impact_date_soonest' => $impactSoonest['date'],
                        'impact_date_highest_ip' => $impactHighestIp['date'],

                        'first_obs' => Arr::get($detail, 'summary.first_obs'),
                        'last_obs' => Arr::get($detail, 'summary.last_obs'),
                        'energy' => $this->sciToFloat(Arr::get($detail, 'summary.energy')),
                        'ip' => $this->sciToFloat(Arr::get($detail, 'summary.ip')),
                        'diameter' => Arr::get($detail, 'summary.diameter'),
                        'mass' => $this->sciToFloat(Arr::get($detail, 'summary.mass')),
                        'ps_cum' => Arr::get($detail, 'summary.ps_cum'),
                        'ts_max' => Arr::get($detail, 'summary.ts_max'),

                        'impacts' => $impacts,
                    ]
                );

            }
        }

        // Get the asteroid with the highest impact probability, according to
        // the highest ps_cum value (Palermo Scale Cumulative Rating)
        $highestRisk = SpaceObject::with('detail')->get()->sortByDesc(function ($obj) {
            // return $obj->detail->ps_cum ?? -9999;
            return $obj->detail->ts_max ?? -9999;
        })->first();

        Cache::put('highest_risk', $highestRisk->id);

        $impactProbability = $highestRisk->ip;
        $ipFormatted = number_format($impactProbability * 100, 2);

        $this->info("Impact probability: {$ipFormatted}%");

        Cache::put('probability', $ipFormatted);

    }

    public function sciToFloat($scientificNotationNumber, $precision = 20)
    {
        return sprintf("%.{$precision}f", $scientificNotationNumber);
    }
}
