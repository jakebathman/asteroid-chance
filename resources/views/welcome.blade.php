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
        <div class="w-full h-full flex flex-col justify-between items-center py-8">
            <model-viewer
                alt="Neil Armstrong's Spacesuit from the Smithsonian Digitization Programs Office and National Air and Space Museum"
                src="/Asteroid (46376) 2001 XD3.gltf"
                poster="Asteroid (46376) 2001 XD3.webp"
                class="h-1/2 w-full mx-auto"
                exposure="0.04"
                shadow-intensity="0.5"
                camera-target="0m 0m 0m"
                auto-rotate
                auto-rotate-delay="0"
                rotation-per-second="15deg"
                camera-controls
                interaction-prompt="none"
            ></model-viewer>
            <div class="font-doto text-white/90 text-center">
                <div class="text-3xl mt-10 w-full flex flex-col items-center gap-4">
                    <div>Impact Probability:</div>
                    <div class="text-6xl">{{ Cache::get('probability') }}%</div>
                </div>
                <div class="grid grid-cols-2 items-center gap-4 mt-10">
                    <div class="text-right">
                        <div>Asteroid:</div>
                        <div>Impact date: </div>
                    </div>
                    <div class="text-left font-bold">
                        <div>{{ $asteroid->designation }}</div>
                        <div>{{ $asteroid->detail->impact_date_highest_ip }}</div>
                    </div>
                </div>
            </div>

            <div class="p-4 text-gray-500 text-sm text-center flex flex-col gap-2">
                <div>
                    Data from NASA JPL's Center for Near Earth Object Studies. Full info for {{ $asteroid->designation }} is on its the <a
                        target="_blank"
                        href="https://cneos.jpl.nasa.gov/sentry/details.html#?des={{ $asteroid->designation }}"
                        class="text-gray-400 hover:underline"
                    >CNEOS Sentry page</a>.
                </div>

                <div>Data updated about every hour, last updated at {{ $asteroid->detail->updated_at }} UTC</div>
                <div>
                    Page created with 💜 by <a
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
            meteorCount: 50,
            width: window.innerWidth,
            height: window.innerHeight,

            init() {
                console.log('Hi, nerds');
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
        }))
    });
</script>

</html>
