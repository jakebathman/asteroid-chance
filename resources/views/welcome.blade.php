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

<body x-data="asteroid">
    <div class="w-full h-dvh bg-gray-800 relative overflow-hidden">
        <template
            x-for="i in meteorCount"
            :key="i"
        >
            <span
                class="animate-meteor-effect absolute top-0 left-1/2 h-0.5 w-0.5 rounded-[9999px] bg-slate-500 shadow-[0_0_0_1px_#ffffff10] rotate-[215deg] before:content-[''] before:absolute before:top-1/2 before:transform before:-translate-y-[50%] before:w-[50px] before:h-[1px] before:bg-gradient-to-r before:from-[#64748b] before:to-transparent"
                :style="asteroidStyle(1)"
            ></span>
        </template>
        <div class="w-full h-full flex flex-col justify-center items-center">
            <model-viewer
                alt="Neil Armstrong's Spacesuit from the Smithsonian Digitization Programs Office and National Air and Space Museum"
                src="/Asteroid (46376) 2001 XD3.gltf"
                poster="Asteroid (46376) 2001 XD3.webp"
                class="h-1/3 mx-auto"
                exposure="0.04"
                shadow-intensity="0.5"
                camera-target="0m 0m 0m"
                auto-rotate
                auto-rotate-delay="0"
                touch-action="pan-y"
            ></model-viewer>
            <div class="font-doto text-3xl text-white/90 mt-10 w-full text-center flex flex-col items-center gap-4">
                <div>Probability:</div>
                <div class="text-6xl">{{ Cache::get('probability') }}%</div>
            </div>
        </div>
    </div>
</body>
<script>
    // Handles loading the events for <model-viewer>'s slotted progress bar
    document.addEventListener('alpine:init', () => {
        Alpine.data('asteroid', () => ({
            meteorCount: 40,
            width: window.innerWidth,
            height: window.innerHeight,

            init() {
                console.log('Asteroid Chance');
            },

            randBetween(min, max) {
                return Math.floor(Math.random() * (max - min + 1) + min);
            },

            asteroidStyle(number) {
                let startOnSide = this.randBetween(0, 1) >= 0.5;

                let left = startOnSide ? -10 : this.randBetween(-800, .9 * this.width);
                let top = !startOnSide ? -10 : this.randBetween(-800, .8 * this.height);
                let duration = this.randBetween(5, 30);
                let delay = this.randBetween(.6, 1);
                console.debug({
                    left,
                    top,
                    duration,
                    delay
                });
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
