<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Asteroid Chance</title>
    <script
        type="module"
        src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"
    ></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    x-data="asteroid"
    class="bg-gray-800"
    x-resize.document="width = $width; height = $height"
>
    <div class="w-full h-dvh bg-gray-800 relative overflow-hidden">
        <template
            x-for="i in meteorCount"
            :key="i"
        >
            <span
                class="animate-meteor-effect absolute top-0 left-1/2 h-0.5 w-0.5 rounded-[9999px] bg-slate-500 shadow-[0_0_0_1px_#ffffff10] rotate-[215deg] before:content-[''] before:absolute before:top-1/2 before:transform before:-translate-y-[50%] before:w-[50px] before:h-[1px] before:bg-gradient-to-r before:from-[#64748b] before:to-transparent"
                :style="asteroidStyle()"
            ></span>
        </template>
        <div class="w-full h-full flex flex-col justify-between items-center py-4 sm:py-6 md:py-8">
            <x-model-viewer class="h-1/3 sm:h-1/2 w-full mx-auto" />

            <div class="font-doto text-white/90 text-center">
                <div class="text-3xl mt-10 w-full flex flex-col items-center gap-4">
                    <div class="animateip">Impact Probability:</div>
                    <div class="text-6xl">{{ Cache::get('probability') }}%</div>
                </div>
                <div class="grid grid-cols-2 items-center gap-4 mt-10">
                    <div class="text-right">
                        <div>Asteroid:</div>
                        <div>Impact date: </div>
                        <div>Torino Scale:</div>
                    </div>
                    <div class="text-left font-bold">
                        <div>{{ $asteroid->designation }}</div>
                        <div>{{ $asteroid->detail->impact_date_highest_ip }}</div>
                        <div class="flex gap-1 items-center">
                            <template x-for="t in tsMax">
                                <div>
                                    {{-- Half of an asteroid (not currently used) --}}
                                    <template x-if="t === 0.5">
                                        <div class="relative h-7 w-7">
                                            <div class="bg-gray-800/85 h-7 w-3 absolute right-0 z-10"></div>
                                            <x-model-viewer
                                                class="h-7 w-7"
                                                exposure=".16"
                                            />
                                        </div>
                                    </template>
                                    <template x-if="t >0">
                                        <div>
                                            <x-model-viewer
                                                class="h-7 w-7"
                                                exposure=".16"
                                            />
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <div class="pl-2">{{ $asteroid->detail->ts_max }}/10</div>
                            <div
                                class="border border-gray-800/40 text-sm rounded-full bg-white/50 text-gray-900 font-bold w-4 h-4 flex justify-center items-center hover:cursor-pointer"
                                @click="showTsHelp = !showTsHelp"
                            >
                                <div
                                    class="pl-0.25"
                                    x-text="showTsHelp ? 'X' : '?'"
                                >?</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="grid grid-rows-[0fr] transition-all duration-200"
                    :class="{ 'grid-rows-[0fr]': !showTsHelp, 'grid-rows-[1fr]': showTsHelp }"
                >
                    <div
                        :class="{ 'border-none opacity-0 m-0 p-0': !showTsHelp, 'm-4 p-3 opacity-100': showTsHelp }"
                        class="grid grid-rows-[0fr] overflow-hidden text-sm text-gray-200 flex-col justify-center items-center gap-3 max-w-[800px] border relative transition-all duration-200"
                    >
                        <div
                            class="absolute top-0 right-0 pt-1.5 pr-2 hover:cursor-pointer"
                            title="Close Torino Scale info box"
                            @click="showTsHelp = false"
                        >X</div>
                        <div class="">A Torino Scale value of <span class="text-white font-bold">{{ $asteroid->detail->ts_max }}</span> means:</div>
                        <div x-text="tsText">
                        </div>
                        <div>More info: <a
                                href="https://cneos.jpl.nasa.gov/sentry/torino_scale.html"
                                target="_blank"
                                class="text-white/90 font-bold hover:underline"
                            >CNEOS Torino Scale</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="p-2.5 sm:p-4 text-gray-500 text-sm text-center flex flex-col gap-2">
                <div class="text-xs md:text-sm">
                    Data from NASA JPL's Center for Near Earth Object Studies. Full info for {{ $asteroid->designation }} is on its the <a
                        target="_blank"
                        href="https://cneos.jpl.nasa.gov/sentry/details.html#?des={{ $asteroid->designation }}"
                        class="text-gray-400 hover:underline"
                    >CNEOS Sentry page</a>. Data updated about every hour, last updated at {{ $asteroid->detail->updated_at }} UTC
                </div>

                <div>
                    Page created with <span class="text-gray-500/50">💜</span> by <a
                        href="https://twitter.com/jakebathman"
                        class="text-gray-400 hover:underline"
                        target="_blank"
                    >@JakeBathman</a> | <a
                        href="https://github.com/jakebathman/asteroid-chance"
                        class="text-gray-400 hover:underline"
                        target="_blank"
                    >Source</a>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    // Handles loading the events for <model-viewer>'s slotted progress bar
    document.addEventListener('alpine:init', () => {
        Alpine.data('asteroid', () => ({
            asteroid: @json($asteroid),
            meteorCount: 50,
            width: window.innerWidth,
            height: window.innerHeight,
            showTsHelp: false,

            init() {
                console.log('Hi, nerds');
            },

            tsMax() {
                console.log('tsMax', this.asteroid.detail.ts_max, this.asteroid);
                let ts = parseInt(this.asteroid.detail.ts_max);
                console.debug(Array(ts).fill(1))
                return Array(ts).fill(1)
                let halfValue = ts / 2;
                let result = Array(Math.floor(halfValue)).fill(1);

                if (!Number.isInteger(halfValue)) {
                    result.push(0.5);
                }

                console.log('tsMax', result);

                return result;
            },

            randBetween(min, max) {
                return Math.floor(Math.random() * (max - min + 1) + min);
            },

            asteroidStyle() {
                let startOnSide = this.randBetween(0, 1) >= 0.5;

                let left = startOnSide ? -10 : this.randBetween(-800, .9 * this.width);
                let top = !startOnSide ? -10 : this.randBetween(-800, .8 * this.height);
                let duration = this.randBetween(10, 60);
                let delay = this.randBetween(.4, 10);
                return `
                            top: ${top}px;
                            left: ${left}px;
                            animation: meteor ${duration}s linear ${delay}s infinite;
                        `
            },

            tsText() {
                let tsValue = this.asteroid.detail.ts_max;
                let descriptions = {
                    0: "The likelihood of a collision is zero, or is so low as to be effectively zero. Also applies to small objects such as meteors and bodies that burn up in the atmosphere as well as infrequent meteorite falls that rarely cause damage.",
                    1: "A routine discovery in which a pass near the Earth is predicted that poses no unusual level of danger. Current calculations show the chance of collision is extremely unlikely with no cause for public attention or public concern. New telescopic observations very likely will lead to re-assignment to Level 0.",
                    2: "A discovery, which may become routine with expanded searches, of an object making a somewhat close but not highly unusual pass near the Earth. While meriting attention by astronomers, there is no cause for public attention or public concern as an actual collision is very unlikely. New telescopic observations very likely will lead to re-assignment to Level 0.",
                    3: "A close encounter, meriting attention by astronomers. Current calculations give a 1% or greater chance of collision capable of localized destruction. Most likely, new telescopic observations will lead to re-assignment to Level 0. Attention by public and by public officials is merited if the encounter is less than a decade away.",
                    4: "A close encounter, meriting attention by astronomers. Current calculations give a 1% or greater chance of collision capable of regional devastation. Most likely, new telescopic observations will lead to re-assignment to Level 0. Attention by public and by public officials is merited if the encounter is less than a decade away.",
                    5: "A close encounter posing a serious, but still uncertain threat of regional devastation. Critical attention by astronomers is needed to determine conclusively whether or not a collision will occur. If the encounter is less than a decade away, governmental contingency planning may be warranted.",
                    6: "A close encounter by a large object posing a serious but still uncertain threat of a global catastrophe. Critical attention by astronomers is needed to determine conclusively whether or not a collision will occur. If the encounter is less than three decades away, governmental contingency planning may be warranted.",
                    7: "A very close encounter by a large object, which if occurring over the next century, poses an unprecedented but still uncertain threat of a global catastrophe. For such a threat, international contingency planning is warranted, especially to determine urgently and conclusively whether or not a collision will occur.",
                    8: "A collision is certain, capable of causing localized destruction for an impact over land or possibly a tsunami if close offshore. Such events occur on average between once per 50 years and once per several 1000 years.",
                    9: "A collision is certain, capable of causing unprecedented regional devastation for a land impact or the threat of a major tsunami for an ocean impact. Such events occur on average between once per 10,000 years and once per 100,000 years.",
                    10: "A collision is certain, capable of causing global climatic catastrophe that may threaten the future of civilization as we know it, whether impacting land or ocean. Such events occur on average once per 100,000 years, or less often.",
                }

                return descriptions[tsValue];
            }
        }))
    });
</script>

</html>
