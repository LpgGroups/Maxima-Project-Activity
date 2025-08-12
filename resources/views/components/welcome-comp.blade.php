@props([
    'name' => 'Pengguna',
    'message' => null,
    'position' => 'br',
    'id' => 'floating-welcome',
    'persist' => false,
])

@php
    $message ??= 'Selamat datang di portal E-Registrasi Maxima';

    // anchor di pojok layar (tombol)
    $posClasses = match ($position) {
        'tl' => 'left-4 top-4',
        'tr' => 'right-4 top-4',
        'bl' => 'left-4 bottom-4',
        default => 'right-4 bottom-4', // br
    };

    // bubble relatif ke tombol (DIAGONAL + gap 4)
    $bubblePos = match ($position) {
        'tl' => 'absolute left-full top-full ml-4 mt-4', // kanan-bawah tombol
        'tr' => 'absolute right-full top-full mr-4 mt-4', // kiri-bawah tombol
        'bl' => 'absolute left-full bottom-full ml-4 mb-4', // kanan-atas tombol
        default => 'absolute right-full bottom-full mr-[6px] -mb-9 w-[200px] h-auto',
    };

    // origin animasi menghadap tombol
    $originClass = match ($position) {
        'tl' => 'origin-top-left',
        'tr' => 'origin-top-right',
        'bl' => 'origin-bottom-left',
        default => 'origin-bottom-right', // br
    };
@endphp

<div id="{{ $id }}"
    class="fixed {{ $posClasses }} z-50 select-none  data-persist="{{ $persist ? 'true' : 'false' }}">
    <div class="relative">
        {{-- BUBBLE --}}
        <div class="{{ $bubblePos }} z-40
                min-w-72 w-max max-w-md
                rounded-2xl shadow-lg bg-white p-4 text-sm leading-snug border border-gray-200
                opacity-0 scale-95 pointer-events-none transition-all duration-200 {{ $originClass }}"
            data-role="bubble" aria-live="polite">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white grid place-items-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.418 0-8 2.239-8 5v1a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-1c0-2.761-3.582-5-8-5Z" />
                    </svg>
                </div>
                <div class="text-gray-800">
                    <p class="font-medium">Halo, <strong>{{ $name }}</strong></p>
                    <p>{{ $message }}</p>
                </div>
            </div>
        </div>

        {{-- BUTTON --}}
        <button type="button"
            class="relative w-10 h-10 rounded-full bg-indigo-600 text-white shadow-lg grid place-items-center
             hover:brightness-110 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition z-30"
            data-role="fab" aria-label="Toggle welcome message" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 pointer-events-none" viewBox="0 0 24 24"
                fill="currentColor">
                <path
                    d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.418 0-8 2.239-8 5v1a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-1c0-2.761-3.582-5-8-5Z" />
            </svg>
        </button>
    </div>
</div>

@once
    @push('scripts')
        <script>
            (() => {
                function setupFloatingWelcome(rootId) {
                    const root = document.getElementById(rootId);
                    if (!root) return;

                    const bubble = root.querySelector('[data-role="bubble"]');
                    const fab = root.querySelector('[data-role="fab"]');
                    if (!bubble || !fab) return;

                    // ===== Toggle bubble =====
                    const bubbleId = rootId + '-bubble';
                    bubble.id = bubbleId;
                    fab.setAttribute('aria-controls', bubbleId);

                    let open = false;
                    const openBubble = (announce = false) => {
                        bubble.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                        bubble.classList.add('opacity-100', 'scale-100');
                        fab.setAttribute('aria-expanded', 'true');
                        open = true;
                        if (announce) setTimeout(closeBubble, 4000);
                    };
                    const closeBubble = () => {
                        bubble.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                        bubble.classList.remove('opacity-100', 'scale-100');
                        fab.setAttribute('aria-expanded', 'false');
                        open = false;
                    };
                    closeBubble();

                    document.addEventListener('click', (e) => {
                        if (!open) return;
                        if (!root.contains(e.target)) closeBubble();
                    });

                    // ===== Draggable + Persist control =====
                    const STORAGE_KEY = `floating-pos:${rootId}`;
                    const persist = root.dataset.persist === 'true';

                    // Jika tidak persist -> HAPUS posisi tersimpan & bersihkan inline (reset tiap refresh)
                    if (!persist) {
                        try {
                            localStorage.removeItem(STORAGE_KEY);
                        } catch {}
                        root.style.left = '';
                        root.style.top = '';
                        root.style.right = '';
                        root.style.bottom = '';
                    }

                    // Apply posisi tersimpan hanya jika persist=true
                    const saved = persist ? localStorage.getItem(STORAGE_KEY) : null;
                    if (saved) {
                        try {
                            const {
                                left,
                                top
                            } = JSON.parse(saved);
                            root.style.right = '';
                            root.style.bottom = '';
                            root.style.left = left + 'px';
                            root.style.top = top + 'px';
                        } catch {}
                    }

                    // Drag handlers
                    const clamp = (v, min, max) => Math.min(Math.max(v, min), max);

                    let dragging = false,
                        startX = 0,
                        startY = 0,
                        origLeft = 0,
                        origTop = 0;
                    let movedEnough = false;
                    const DRAG_THRESHOLD = 4; // px

                    const onPointerDown = (e) => {
                        e.preventDefault();

                        const rect = root.getBoundingClientRect();
                        root.style.right = '';
                        root.style.bottom = '';
                        root.style.left = rect.left + 'px';
                        root.style.top = rect.top + 'px';

                        dragging = true;
                        movedEnough = false;
                        startX = e.clientX;
                        startY = e.clientY;
                        origLeft = rect.left;
                        origTop = rect.top;

                        fab.setPointerCapture?.(e.pointerId);
                    };

                    const onPointerMove = (e) => {
                        if (!dragging) return;

                        const dx = e.clientX - startX;
                        const dy = e.clientY - startY;

                        if (!movedEnough && Math.hypot(dx, dy) > DRAG_THRESHOLD) {
                            movedEnough = true;
                            if (open) closeBubble();
                        }

                        const vw = window.innerWidth;
                        const vh = window.innerHeight;
                        const rootRect = root.getBoundingClientRect();
                        const w = rootRect.width || 56;
                        const h = rootRect.height || 56;

                        let nextLeft = origLeft + dx;
                        let nextTop = origTop + dy;

                        nextLeft = clamp(nextLeft, 0, vw - w);
                        nextTop = clamp(nextTop, 0, vh - h);

                        root.style.left = nextLeft + 'px';
                        root.style.top = nextTop + 'px';
                    };

                    const onPointerUp = (e) => {
                        if (!dragging) return;
                        dragging = false;

                        // Simpan posisi HANYA jika persist=true
                        if (persist) {
                            const rect = root.getBoundingClientRect();
                            try {
                                localStorage.setItem(STORAGE_KEY, JSON.stringify({
                                    left: rect.left,
                                    top: rect.top
                                }));
                            } catch {}
                        }

                        // Klik (tanpa drag) -> toggle bubble
                        if (!movedEnough) {
                            e.stopPropagation();
                            open ? closeBubble() : openBubble(false);
                        }
                    };

                    fab.addEventListener('pointerdown', onPointerDown, {
                        passive: false
                    });
                    window.addEventListener('pointermove', onPointerMove, {
                        passive: false
                    });
                    window.addEventListener('pointerup', onPointerUp, {
                        passive: true
                    });

                    // Tampilkan sekali saat load pertama (opsional)
                    if (!saved) setTimeout(() => openBubble(true), 800);
                }

                document.querySelectorAll('[id^="floating-welcome"]').forEach(el => setupFloatingWelcome(el.id));
            })
            ();
        </script>
    @endpush
@endonce
