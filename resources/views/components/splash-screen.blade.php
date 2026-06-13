<div id="splash-screen" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 transition-opacity duration-700 ease-out" style="display: none; opacity: 1; pointer-events: auto;">
    <div class="flex flex-col items-center max-w-sm px-6 text-center">
        <!-- Logo container with pulsing effect -->
        <div class="relative mb-6">
            <div class="absolute -inset-4 rounded-full bg-cyan-500/20 blur-xl animate-pulse"></div>
            <div class="relative w-24 h-24 rounded-3xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center shadow-2xl shadow-blue-500/30 animate-bounce-slow">
                <!-- Premium cross icon -->
                <svg class="w-12 h-12 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
        </div>

        <!-- Clinic name & sub -->
        <h2 class="text-white font-extrabold text-3xl tracking-tight mb-2 opacity-0 animate-fade-in-up">
            {{ $clinicSettings->nom_clinique }}
        </h2>
        <p class="text-slate-400 text-sm font-medium mb-8 opacity-0 animate-fade-in-up-delay">
            {{ $clinicSettings->slogan ?: 'Système de gestion médicale' }}
        </p>

        <!-- Loader -->
        <div class="w-48 h-1 bg-slate-800 rounded-full overflow-hidden mb-4 opacity-0 animate-fade-in-up-delay-2">
            <div class="h-full bg-gradient-to-r from-cyan-400 to-blue-600 rounded-full animate-loading-bar" style="width: 0%;"></div>
        </div>
        
        <span class="text-xs text-slate-500 font-semibold tracking-widest uppercase opacity-0 animate-fade-in-up-delay-2">
            Chargement...
        </span>
    </div>
</div>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 3s ease-in-out infinite;
    }
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out forwards;
        animation-delay: 0.2s;
    }
    .animate-fade-in-up-delay {
        animation: fade-in-up 0.8s ease-out forwards;
        animation-delay: 0.4s;
    }
    .animate-fade-in-up-delay-2 {
        animation: fade-in-up 0.8s ease-out forwards;
        animation-delay: 0.6s;
    }
    @keyframes loading-bar {
        0% { width: 0%; }
        50% { width: 70%; }
        100% { width: 100%; }
    }
    .animate-loading-bar {
        animation: loading-bar 1.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
</style>

<script>
    (function() {
        const splash = document.getElementById('splash-screen');
        if (!splash) return;

        // Check if splash has already been shown in this browser session
        if (sessionStorage.getItem('splash_shown')) {
            splash.style.display = 'none';
            return;
        }

        // Show splash screen immediately
        splash.style.display = 'flex';
        // Lock interactions during splash
        document.body.style.overflow = 'hidden';

        window.addEventListener('load', function() {
            setTimeout(function() {
                // Fade out
                splash.style.opacity = '0';
                splash.style.pointerEvents = 'none';
                
                // Restore overflow
                document.body.style.overflow = '';
                
                // Set session storage flag
                sessionStorage.setItem('splash_shown', 'true');

                // Remove from DOM after fade out transition
                setTimeout(function() {
                    splash.remove();
                }, 700);
            }, 1200); // 1.2s show time
        });

        // Fallback in case load event takes too long
        setTimeout(function() {
            if (document.body.style.overflow === 'hidden') {
                splash.style.opacity = '0';
                splash.style.pointerEvents = 'none';
                document.body.style.overflow = '';
                sessionStorage.setItem('splash_shown', 'true');
                setTimeout(function() {
                    splash.remove();
                }, 700);
            }
        }, 3000);
    })();
</script>
