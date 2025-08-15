{{-- resources/views/components/detail-training.blade.php --}}
@props([
    'training',                // instance RegTraining (with 'approvalFiles' relation)
    'dateFmt' => null,         // tanggal sudah diformat di controller
    'showFiles' => true,       // toggle list file approval
    'backUrl' => null,         // optional: url tombol kembali
])

@php
    // Guard & helper
    $noLetter   = $training->no_letter ?? '-';
    $pic        = $training->name_pic ?? '-';
    $company    = $training->name_company ?? '-';
    $activity   = $training->activity ?? '-';
    $prov       = $training->provience ?? '-';
    $city       = $training->city ?? '-';
    $dateSafe   = $dateFmt ?? '-';
    $progress   = (int) ($training->progress_percent ?? 0);
    $progress   = max(0, min($progress, 100));
    $hasFiles   = $training->approvalFiles && $training->approvalFiles->count() > 0;
    $hasProof   = $hasFiles && $training->approvalFiles->firstWhere('proof_payment', '!=', null);
    $badgeText  = $hasProof ? 'Sudah diupload' : 'Belum diupload';
    $badgeClass = $hasProof
        ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200'
        : 'bg-amber-100 text-amber-700 ring-1 ring-amber-200';

    // Avatar inisial PIC (optional garnish)
    $initials = collect(explode(' ', trim($pic)))
                    ->filter()
                    ->map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)))
                    ->take(2)->implode('');

@endphp

<div class="relative overflow-hidden rounded-2xl border bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-neutral-900 dark:ring-neutral-800">
    {{-- Header strip --}}
    <div class="relative">
        <div class="h-28 bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600"></div>
        <div class="absolute inset-0 opacity-15 mix-blend-overlay pointer-events-none bg-[radial-gradient(60rem_60rem_at_80%_-10%,white,transparent)]"></div>

        {{-- Header content --}}
        <div class="px-6 sm:px-8 -mt-16 pb-4">
            <div class="flex items-end justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="text-white">
                        <h2 class="text-xl sm:text-2xl font-semibold leading-tight drop-shadow">
                          {{ $company }}
                        </h2>
                        <p class="text-sm/6 opacity-90">
                             {{ config('activity_map.' . $training->activity) ?? $training->activity }}
                        </p>
                    </div>
                </div>

                {{-- Kembali / Actions slot --}}
                <div class="flex items-center gap-2">
                    @if($backUrl)
                        <a href="{{ $backUrl }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-white/90 px-3 py-2 text-sm font-medium text-gray-800 ring-1 ring-white/70 shadow-sm hover:bg-white dark:bg-neutral-800 dark:text-neutral-100 dark:ring-neutral-700">
                            {{-- icon chevron-left --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Kembali
                        </a>
                    @endif

                    {{-- Custom actions dari luar --}}
                    {{ $actions ?? '' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Body --}}
    <div class="grid grid-cols-1 gap-6 px-6 pb-6 sm:px-8 sm:pb-8 lg:grid-cols-12">
        {{-- Kartu ringkasan kiri --}}
        <div class="lg:col-span-7 space-y-6">
            <div class="rounded-2xl border bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-neutral-900 dark:ring-neutral-800">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-xl bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 ring-1 ring-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-200 dark:ring-indigo-800/60">
                        {{-- icon tag --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L3 13.99V4h9l8.59 8.59a2 2 0 010 2.82zM7.5 7.5a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"/>
                        </svg>
                        No Surat: <span class="font-semibold">{{ $noLetter }}</span>
                    </span>

                    <span class="inline-flex items-center gap-2 rounded-xl px-3 py-1.5 text-xs font-medium ring-1 {{ $badgeClass }}">
                        {{-- icon status --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                            @if($hasProof)
                                <path d="M9 12l2 2 4-4 1.5 1.5L11 16l-3.5-3.5L9 12z"/>
                            @else
                                <path d="M12 22a10 10 0 110-20 10 10 0 010 20zm1-5h-2v2h2v-2zm0-10h-2v8h2V7z"/>
                            @endif
                        </svg>
                        {{ $badgeText }}
                    </span>
                </div>

                <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border p-4 ring-1 ring-gray-950/5 dark:border-neutral-800 dark:ring-neutral-800">
                        <dt class="text-xs font-medium text-gray-500 dark:text-neutral-400">PIC</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-neutral-50">{{ $pic }}</dd>
                    </div>
                    <div class="rounded-xl border p-4 ring-1 ring-gray-950/5 dark:border-neutral-800 dark:ring-neutral-800">
                        <dt class="text-xs font-medium text-gray-500 dark:text-neutral-400">Perusahaan</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-neutral-50">{{ $company }}</dd>
                    </div>
                    <div class="rounded-xl border p-4 ring-1 ring-gray-950/5 dark:border-neutral-800 dark:ring-neutral-800">
                        <dt class="text-xs font-medium text-gray-500 dark:text-neutral-400">Lokasi Pelatihan</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-neutral-50">{{ $prov }}, {{ $city }}</dd>
                    </div>
                    <div class="rounded-xl border p-4 ring-1 ring-gray-950/5 dark:border-neutral-800 dark:ring-neutral-800">
                        <dt class="text-xs font-medium text-gray-500 dark:text-neutral-400">Tanggal Pelatihan</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-neutral-50">{{ $dateSafe }}</dd>
                    </div>
                </dl>

                {{-- Progress --}}
                <div class="mt-5">
                    
                    <div class="mt-2 h-3 w-full overflow-hidden rounded-full bg-gray-100 ring-1 ring-gray-950/5 dark:bg-neutral-800 dark:ring-neutral-700">
                        <div
                            class="h-full rounded-full bg-gradient-to-r from-emerald-500 via-lime-500 to-yellow-400 transition-all duration-700"
                           
                            aria-label="progress bar">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Catatan / slot konten tambahan --}}
            @if (trim($slot) !== '')
                <div class="rounded-2xl border bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-neutral-900 dark:ring-neutral-800">
                    {{ $slot }}
                </div>
            @endif
        </div>

        {{-- Sidebar kanan --}}
        <div class="lg:col-span-5 space-y-6">
            {{-- Files --}}
            @if($showFiles)
                <div class="rounded-2xl border bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-neutral-900 dark:ring-neutral-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-neutral-50">Bukti Pembayaran Pelatihan</h3>
                        <span class="text-xs text-gray-500 dark:text-neutral-400">{{ $training->approvalFiles?->count() ?? 0 }} file</span>
                    </div>

                    @if($hasFiles)
                        <ul class="mt-4 space-y-2">
                            @foreach($training->approvalFiles as $f)
                                @php
                                    $isProof = !empty($f->proof_payment);
                                    $label   = $isProof ? 'Proof Payment' : 'Lampiran';
                                    $href    = $isProof ? asset('storage/'.$f->proof_payment) : null;
                                @endphp
                                <li class="group flex items-center justify-between gap-3 rounded-xl border p-3 ring-1 ring-gray-950/5 hover:bg-gray-50 dark:border-neutral-800 dark:ring-neutral-800 dark:hover:bg-neutral-800/40">
                                    <div class="flex items-center gap-3">
                                        {{-- icon file --}}
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-700 ring-1 ring-gray-950/5 dark:bg-neutral-800 dark:text-neutral-200 dark:ring-neutral-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zM8 12h8v2H8v-2zm0 4h8v2H8v-2zm6-9h4v.01L14 3.99V7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-neutral-50">{{ $label }}</p>
                                            <p class="text-xs text-gray-500 dark:text-neutral-400">
                                                {{ $isProof ? 'Bukti pembayaran terunggah' : 'Belum ada bukti' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        @if($href)
                                            <a href="{{ $href }}" target="_blank"
                                               class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-3 py-2 text-xs font-medium text-white shadow-sm hover:opacity-90 dark:bg-neutral-700">
                                                Lihat
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path d="M7 17L17 7M7 7h10v10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                        @else
                                            <span class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-1.5 text-xs font-medium text-gray-500 ring-1 ring-gray-950/5 dark:bg-neutral-800 dark:text-neutral-400 dark:ring-neutral-700">
                                                Tidak tersedia
                                            </span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="mt-4 rounded-xl border border-dashed p-6 text-center text-sm text-gray-500 ring-1 ring-gray-950/5 dark:border-neutral-800 dark:text-neutral-400 dark:ring-neutral-800">
                            Belum ada file.
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
