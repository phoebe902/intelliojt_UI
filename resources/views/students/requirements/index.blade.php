@php
    $icons = [
        'check'    => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'clock'    => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'alert'    => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-4.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
        'upload'   => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5',
        'calendar' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
    ];

    // status => badge classes, number-circle classes, action label
    $statuses = [
        'approved' => ['label' => 'Approved',        'badge' => 'bg-emerald-50 text-emerald-700', 'num' => 'bg-emerald-50 text-emerald-700', 'action' => 'View'],
        'review'   => ['label' => 'Under review',    'badge' => 'bg-blue-50 text-blue-700',       'num' => 'bg-blue-50 text-blue-700',       'action' => 'View'],
        'revision' => ['label' => 'Revision needed', 'badge' => 'bg-amber-50 text-amber-700',     'num' => 'bg-amber-50 text-amber-700',     'action' => 'Re-upload'],
        'missing'  => ['label' => 'Not submitted',   'badge' => 'bg-slate-100 text-slate-600',    'num' => 'bg-slate-100 text-slate-600',    'action' => 'Upload'],
        'locked'   => ['label' => 'Opens later',     'badge' => 'bg-slate-50 text-slate-400',     'num' => 'bg-slate-50 text-slate-400',     'action' => null],
    ];

    $phases = [
        'before' => [
            'label' => 'Before OJT duty', 'subtitle' => 'Pre-deployment compliance',
            'items' => [
                ['title' => "Parent's/Guardian's Consent",       'desc' => 'Notarized copy required',            'status' => 'approved', 'note' => 'Submitted Jan 03'],
                ['title' => 'Internship Agreement',              'desc' => 'Between student and HTE; notarized', 'status' => 'approved', 'note' => 'Submitted Jan 05'],
                ['title' => 'Signed Internship Plan',            'desc' => null,                                 'status' => 'approved', 'note' => 'Submitted Jan 06'],
                ['title' => 'Certification of Fitness to Work',  'desc' => 'Medical certificate',                'status' => 'review',   'note' => 'Submitted Jan 09'],
                ['title' => 'COVID-19 Vaccination Card',         'desc' => 'At least 2 doses; 1 dose if J&J',    'status' => 'approved', 'note' => 'Submitted Jan 04'],
                ['title' => 'Group Insurance or PhilHealth',     'desc' => 'Group insurance, or PhilHealth ID/MDR when OJT is outside the home city/municipality', 'status' => 'revision', 'note' => 'Notary seal is not visible. Please re-upload a clear copy.'],
                ['title' => 'Pre-departure Orientation Seminar', 'desc' => 'Participation in PDOS',              'status' => 'approved', 'note' => 'Verified by coordinator'],
            ],
        ],
        'during' => [
            'label' => 'During OJT duty', 'subtitle' => 'Ongoing submissions',
            'items' => [
                ['title' => 'Daily Time Record',              'desc' => 'Signed by HTE supervisor, submitted weekly', 'status' => 'approved', 'note' => 'Week 10 submitted'],
                ['title' => 'Weekly Accomplishment Report',   'desc' => 'Summary of tasks and learnings',             'status' => 'missing',  'note' => 'Due Wed, Nov 11'],
                ['title' => 'Mid-term Supervisor Evaluation', 'desc' => 'Performance rating from HTE',                'status' => 'review',   'note' => 'Submitted Nov 02'],
                ['title' => 'Monitoring Visit Form',          'desc' => 'Completed during coordinator visit',         'status' => 'approved', 'note' => 'Visited Oct 21'],
            ],
        ],
        'after' => [
            'label' => 'After OJT duty', 'subtitle' => 'Post-deployment completion',
            'items' => [
                ['title' => 'Final Narrative Report',        'desc' => 'Complete report with documentation', 'status' => 'locked', 'note' => 'Opens at 450 hours'],
                ['title' => "Supervisor's Final Evaluation", 'desc' => 'Sealed by HTE supervisor',           'status' => 'locked', 'note' => 'Opens after OJT'],
                ['title' => 'Certificate of Completion',     'desc' => 'Issued by the host company',         'status' => 'locked', 'note' => 'Opens after OJT'],
                ['title' => 'Exit Survey',                   'desc' => 'Feedback on the program',            'status' => 'locked', 'note' => 'Opens after OJT'],
            ],
        ],
    ];

    $allItems  = collect($phases)->flatMap(fn ($p) => $p['items']);
    $countOf   = fn ($s) => $allItems->where('status', $s)->count();
    $submittedCount = $countOf('approved') + $countOf('review');

    $summaryCards = [
        ['icon' => 'check', 'tone' => 'bg-emerald-50 text-emerald-600', 'value' => $countOf('approved'), 'label' => 'Approved',      'note' => 'Verified by your coordinator'],
        ['icon' => 'clock', 'tone' => 'bg-blue-50 text-blue-600',       'value' => $countOf('review'),   'label' => 'Under review',  'note' => 'Waiting for validation'],
        ['icon' => 'alert', 'tone' => 'bg-amber-50 text-amber-600',     'value' => $countOf('revision') + $countOf('missing'), 'label' => 'Needs action', 'note' => 'Upload or fix these first'],
    ];
@endphp

<x-layouts.app title="My requirements">

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Submission center</p>
            <h1 class="mt-2 text-4xl font-normal tracking-tight text-slate-900">My requirements</h1>
            <p class="mt-2 text-sm text-slate-500">Upload each document, track its review status, and fix anything flagged by your coordinator.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-right shadow-sm">
            <p class="text-xs text-slate-400">Overall submitted</p>
            <p class="text-2xl font-semibold text-slate-900">{{ $submittedCount }}<span class="text-sm font-normal text-slate-400"> / {{ $allItems->count() }}</span></p>
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="mt-8 grid grid-cols-1 gap-5 md:grid-cols-3">
        @foreach ($summaryCards as $card)
            <div class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div>
                    <p class="text-xs text-slate-500">{{ $card['label'] }}</p>
                    <p class="mt-2 text-3xl font-medium text-slate-900">{{ $card['value'] }}</p>
                    <p class="mt-1 text-[11px] text-slate-400">{{ $card['note'] }}</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $card['tone'] }}">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $icons[$card['icon']] }}" />
                    </svg>
                </span>
            </div>
        @endforeach
    </div>

    <div x-data="{ tab: 'before' }" class="mt-6">

        {{-- Phase tabs --}}
        <div class="inline-flex gap-1 rounded-2xl border border-slate-200 bg-white p-1.5 shadow-sm">
            @foreach ($phases as $key => $phase)
                <button type="button"
                        @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-500 hover:bg-slate-50'"
                        class="rounded-xl px-5 py-2.5 text-sm font-semibold transition">
                    {{ $phase['label'] }}
                </button>
            @endforeach
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-[1fr_340px]">

            {{-- Requirement list --}}
            <div>
                @foreach ($phases as $key => $phase)
                    <section x-show="tab === '{{ $key }}'" @if (! $loop->first) x-cloak @endif
                             class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <header class="border-b border-slate-100 p-6">
                            <h2 class="text-lg text-slate-900">{{ $phase['label'] }}</h2>
                            <p class="mt-1 text-xs text-slate-400">{{ $phase['subtitle'] }} &middot; {{ count($phase['items']) }} requirements</p>
                        </header>

                        <ul class="divide-y divide-slate-100">
                            @foreach ($phase['items'] as $item)
                                @php $st = $statuses[$item['status']]; @endphp
                                <li class="flex flex-wrap items-center gap-4 px-6 py-5 transition hover:bg-slate-50/70">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold {{ $st['num'] }}">
                                        {{ $loop->iteration }}
                                    </span>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-slate-900">{{ $item['title'] }}</p>
                                        @if ($item['desc'])
                                            <p class="mt-1 text-xs leading-relaxed text-slate-400">{{ $item['desc'] }}</p>
                                        @endif
                                        <p class="mt-1.5 text-xs {{ $item['status'] === 'revision' ? 'font-medium text-amber-600' : 'text-slate-400' }}">
                                            {{ $item['note'] }}
                                        </p>
                                    </div>

                                    <span class="whitespace-nowrap rounded-full px-3 py-1 text-[11px] font-semibold {{ $st['badge'] }}">
                                        {{ $st['label'] }}
                                    </span>

                                    <div class="w-28 text-right">
                                        @if ($st['action'] === 'Upload' || $st['action'] === 'Re-upload')
                                            <label class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition
                                                {{ $st['action'] === 'Upload' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-amber-500 hover:bg-amber-600' }}">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="{{ $icons['upload'] }}" />
                                                </svg>
                                                {{ $st['action'] }}
                                                <input type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                            </label>
                                        @elseif ($st['action'] === 'View')
                                            <button type="button" class="rounded-lg border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                                                View
                                            </button>
                                        @else
                                            <span class="text-xs text-slate-300">&mdash;</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endforeach
            </div>

            {{-- Side panel --}}
            <aside class="space-y-6 self-start">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 rounded-xl bg-blue-50 p-4">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $icons['calendar'] }}" />
                        </svg>
                        <div>
                            <p class="text-[10px] text-slate-400">Next deadline</p>
                            <p class="text-sm font-semibold text-slate-800">Weekly report &middot; Nov 11, 2026</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg text-slate-900">Upload guidelines</h2>
                    <ul class="mt-4 space-y-3 text-xs leading-relaxed text-slate-500">
                        <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-500"></span>Accepted formats: PDF, JPG or PNG, up to 5 MB per file.</li>
                        <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-500"></span>Scan documents flat and make sure signatures and seals are readable.</li>
                        <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-500"></span>Notarized documents must show the notary seal.</li>
                        <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-500"></span>Review usually takes 1&ndash;2 working days.</li>
                    </ul>
                </section>
            </aside>
        </div>
    </div>
</x-layouts.app>