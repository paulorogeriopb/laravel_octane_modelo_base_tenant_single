<div class="fixed inset-0 z-50 flex items-center justify-center bg-white/20 backdrop-blur-sm">
    <div class="relative flex items-center justify-center w-28 h-28">
        {{-- Anel giratório principal --}}
        <div
            class="absolute inset-0 rounded-full border-4 border-t-transparent border-r-transparent border-b-transparent border-l-[#32a2b9] animate-spin shadow-[0_0_20px_rgba(50,162,185,0.7)]">
        </div>

        {{-- Halo pulsante --}}
        <div
            class="absolute inset-1 rounded-full border-2 border-[#298ba1] opacity-50 animate-pulse shadow-[0_0_15px_rgba(41,139,161,0.5)]">
        </div>

        {{-- Nome central --}}
        <div class="relative text-[#32a2b9] font-bold text-lg select-none drop-shadow-[0_0_10px_rgba(50,162,185,0.8)]">
            Nimbus
        </div>
    </div>
</div>

<style>
    @keyframes nimbus-pulse {
        0% {
            transform: translateX(0px) rotate(0deg);
            opacity: 0;
        }

        25% {
            opacity: 1;
            transform: translateX(40px) rotate(90deg);
        }

        50% {
            opacity: 0.6;
            transform: translateX(50px) rotate(180deg);
        }

        75% {
            opacity: 0.3;
            transform: translateX(40px) rotate(270deg);
        }

        100% {
            transform: translateX(0px) rotate(360deg);
            opacity: 0;
        }
    }

    .animate-nimbus-pulse {
        animation: nimbus-pulse 2s linear infinite;
    }
</style>
