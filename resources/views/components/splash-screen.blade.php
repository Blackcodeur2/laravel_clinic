<div id="splash-screen" style="display:none; position:fixed; inset:0; z-index:9999; overflow:hidden;">

    {{-- Animated background --}}
    <div class="splash-bg">
        <div class="splash-orb splash-orb-1"></div>
        <div class="splash-orb splash-orb-2"></div>
        <div class="splash-orb splash-orb-3"></div>
    </div>

    {{-- Content --}}
    <div class="splash-content">

        {{-- Logo + glow ring --}}
        <div class="splash-logo-wrap">
            <div class="splash-glow-ring"></div>
            <div class="splash-logo-box">
                <img src="{{ asset('images/icons/icon-192x192.png') }}"
                     alt="{{ $clinicSettings->nom_clinique ?? 'Clinique' }}"
                     class="splash-logo-img">
            </div>
        </div>

        {{-- Clinic name --}}
        <h1 class="splash-title">
            {{ $clinicSettings->nom_clinique ?? 'MedFacture' }}
        </h1>

        {{-- Subtitle --}}
        <p class="splash-subtitle">
            {{ $clinicSettings->slogan ?: 'Gestion & Facturation Clinique' }}
        </p>

        {{-- Divider --}}
        <div class="splash-divider">
            <span class="splash-divider-line"></span>
            <span class="splash-divider-icon">✦</span>
            <span class="splash-divider-line"></span>
        </div>

        {{-- Progress bar --}}
        <div class="splash-progress-track">
            <div class="splash-progress-bar" id="splash-progress-bar"></div>
        </div>

        <span class="splash-loading-text">Chargement en cours…</span>
    </div>

    {{-- Version badge --}}
    <div class="splash-footer">
        <span class="splash-version">v{{ config('app.version', '1.0') }}</span>
    </div>
</div>

<style>
    /* ── Base ─────────────────────────────────────── */
    #splash-screen {
        font-family: 'Inter', 'Segoe UI', sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #0A1628;
        opacity: 1;
        transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: auto;
    }

    /* ── Animated gradient bg ─────────────────────── */
    .splash-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(160deg, #0A1628 0%, #0F2347 50%, #0A1628 100%);
        overflow: hidden;
    }
    .splash-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.35;
        animation: orbFloat 8s ease-in-out infinite;
    }
    .splash-orb-1 {
        width: 400px; height: 400px;
        background: radial-gradient(circle, #1d4ed8, transparent);
        top: -100px; left: -100px;
        animation-delay: 0s;
    }
    .splash-orb-2 {
        width: 300px; height: 300px;
        background: radial-gradient(circle, #06b6d4, transparent);
        bottom: -80px; right: -80px;
        animation-delay: 3s;
    }
    .splash-orb-3 {
        width: 200px; height: 200px;
        background: radial-gradient(circle, #3b82f6, transparent);
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        animation-delay: 5s;
    }
    @keyframes orbFloat {
        0%, 100% { transform: scale(1) translate(0, 0); }
        50%       { transform: scale(1.15) translate(10px, -10px); }
    }

    /* ── Content wrapper ──────────────────────────── */
    .splash-content {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 2rem;
        animation: splashContentIn 0.6s ease-out forwards;
    }
    @keyframes splashContentIn {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Logo ─────────────────────────────────────── */
    .splash-logo-wrap {
        position: relative;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .splash-glow-ring {
        position: absolute;
        width: 140px; height: 140px;
        border-radius: 50%;
        background: conic-gradient(from 0deg, #06b6d4, #3b82f6, #1d4ed8, #06b6d4);
        animation: rotateSpin 4s linear infinite;
        opacity: 0.6;
        filter: blur(2px);
    }
    @keyframes rotateSpin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }
    .splash-logo-box {
        position: relative;
        width: 110px; height: 110px;
        border-radius: 28px;
        background: linear-gradient(135deg, #0f2347, #1a3a6b);
        border: 2px solid rgba(6, 182, 212, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 0 40px rgba(6, 182, 212, 0.2),
            0 20px 60px rgba(0, 0, 0, 0.5),
            inset 0 1px 0 rgba(255,255,255,0.1);
        animation: logoPulse 3s ease-in-out infinite;
    }
    @keyframes logoPulse {
        0%, 100% { box-shadow: 0 0 40px rgba(6,182,212,0.2), 0 20px 60px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.1); }
        50%       { box-shadow: 0 0 70px rgba(6,182,212,0.45), 0 20px 60px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.1); }
    }
    .splash-logo-img {
        width: 72px; height: 72px;
        border-radius: 16px;
        object-fit: contain;
    }

    /* ── Text ─────────────────────────────────────── */
    .splash-title {
        font-size: clamp(1.6rem, 5vw, 2.4rem);
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.02em;
        margin: 0 0 0.4rem 0;
        text-shadow: 0 0 30px rgba(6, 182, 212, 0.4);
        animation: fadeInUp 0.7s ease-out 0.3s both;
    }
    .splash-subtitle {
        font-size: clamp(0.8rem, 2.5vw, 1rem);
        font-weight: 500;
        color: #7fb3f5;
        letter-spacing: 0.04em;
        margin: 0 0 1.8rem 0;
        animation: fadeInUp 0.7s ease-out 0.5s both;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Divider ──────────────────────────────────── */
    .splash-divider {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.8rem;
        animation: fadeInUp 0.7s ease-out 0.6s both;
    }
    .splash-divider-line {
        display: block;
        width: 60px; height: 1px;
        background: linear-gradient(90deg, transparent, rgba(6,182,212,0.5), transparent);
    }
    .splash-divider-icon {
        color: #06b6d4;
        font-size: 0.6rem;
        opacity: 0.7;
    }

    /* ── Progress bar ─────────────────────────────── */
    .splash-progress-track {
        width: min(220px, 60vw);
        height: 3px;
        background: rgba(255,255,255,0.08);
        border-radius: 999px;
        overflow: hidden;
        margin-bottom: 0.75rem;
        animation: fadeInUp 0.7s ease-out 0.7s both;
    }
    .splash-progress-bar {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #06b6d4, #3b82f6);
        border-radius: 999px;
        box-shadow: 0 0 10px rgba(6,182,212,0.6);
        transition: width 0.1s linear;
    }
    .splash-loading-text {
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.3);
        animation: fadeInUp 0.7s ease-out 0.8s both;
    }

    /* ── Footer ───────────────────────────────────── */
    .splash-footer {
        position: absolute;
        bottom: 2rem;
        z-index: 1;
        animation: fadeInUp 0.7s ease-out 1s both;
    }
    .splash-version {
        font-size: 0.6rem;
        color: rgba(255,255,255,0.15);
        letter-spacing: 0.1em;
        font-weight: 500;
    }
</style>

<script>
(function () {
    const splash = document.getElementById('splash-screen');
    if (!splash) return;

    if (sessionStorage.getItem('splash_shown')) {
        splash.style.display = 'none';
        return;
    }

    splash.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    // Animate progress bar
    const bar = document.getElementById('splash-progress-bar');
    let progress = 0;
    const interval = setInterval(function () {
        progress = Math.min(progress + Math.random() * 8, 92);
        if (bar) bar.style.width = progress + '%';
    }, 80);

    function hideSplash() {
        clearInterval(interval);
        if (bar) bar.style.width = '100%';
        setTimeout(function () {
            splash.style.opacity = '0';
            splash.style.pointerEvents = 'none';
            document.body.style.overflow = '';
            sessionStorage.setItem('splash_shown', 'true');
            setTimeout(function () { splash.remove(); }, 800);
        }, 300);
    }

    window.addEventListener('load', function () {
        setTimeout(hideSplash, 1400);
    });

    // Hard fallback at 4s
    setTimeout(function () {
        if (document.body.style.overflow === 'hidden') hideSplash();
    }, 4000);
})();
</script>
