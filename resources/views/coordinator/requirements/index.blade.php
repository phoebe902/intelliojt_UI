@php
    $icons = [
        'check'    => 'M4.5 12.75l6 6 9-13.5',
        'clock'    => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'document' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
        'download' => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3',
        'chevron'  => 'M8.25 4.5l7.5 7.5-7.5 7.5',
    ];

    $summaryCards = [
        ['icon' => 'check',    'tone' => 'bg-emerald-50 text-emerald-600', 'value' => '89%', 'label' => 'Overall compliance', 'note' => '1,252 of 1,408 submissions'],
        ['icon' => 'clock',    'tone' => 'bg-amber-50 text-amber-600',     'value' => '21',  'label' => 'Pending review',     'note' => 'Documents need validation'],
        ['icon' => 'document', 'tone' => 'bg-rose-50 text-rose-600',       'value' => '9',   'label' => 'Overdue',            'note' => 'Across 7 students'],
    ];

    $tones = [
        'green' => ['num' => 'bg-emerald-50 text-emerald-700', 'bar' => 'bg-emerald-500'],
        'amber' => ['num' => 'bg-amber-50 text-amber-700',     'bar' => 'bg-amber-500'],
        'red'   => ['num' => 'bg-rose-50 text-rose-700',       'bar' => 'bg-rose-500'],
    ];

    $avatarTones = [
        'green' => 'bg-emerald-50 text-emerald-700',
        'amber' => 'bg-amber-50 text-amber-700',
        'blue'  => 'bg-blue-50 text-blue-700',
        'red'   => 'bg-rose-50 text-rose-700',
    ];

    $phases = [
        'before' => [
            'label'    => 'Before OJT duty',
            'subtitle' => 'Pre-deployment compliance',
            'percent'  => 95,
            'items'    => [
                ['title' => "Parent's/Guardian's Consent",      'desc' => 'Notarized copy required',                'done' => 118, 'total' => 124, 'tone' => 'amber'],
                ['title' => 'Internship Agreement',             'desc' => 'Between student and HTE; notarized',     'done' => 112, 'total' => 124, 'tone' => 'red'],
                ['title' => 'Signed Internship Plan',           'desc' => null,                                     'done' => 121, 'total' => 124, 'tone' => 'green'],
                ['title' => 'Certification of Fitness to Work', 'desc' => 'Medical certificate',                    'done' => 116, 'total' => 124, 'tone' => 'amber'],
                ['title' => 'COVID-19 Vaccination Card',        'desc' => 'At least 2 doses; 1 dose if J&J',        'done' => 123, 'total' => 124, 'tone' => 'green'],
                ['title' => 'Group Insurance or PhilHealth',    'desc' => 'Group insurance via ML, GCash, Palawan, etc., or PhilHealth ID/MDR when OJT is outside the home city/municipality', 'done' => 109, 'total' => 124, 'tone' => 'red'],
                ['title' => 'Pre-departure Orientation Seminar','desc' => 'Participation in PDOS',                 'done' => 124, 'total' => 124, 'tone' => 'green'],
            ],
        ],
        'during' => [
            'label'    => 'During OJT duty',
            'subtitle' => 'Ongoing submissions',
            'percent'  => 88,
            'items'    => [
                ['title' => 'Daily Time Record',              'desc' => 'Signed by HTE supervisor, submitted weekly', 'done' => 120, 'total' => 124, 'tone' => 'green'],
                ['title' => 'Weekly Accomplishment Report',   'desc' => 'Summary of tasks and learnings',             'done' => 104, 'total' => 124, 'tone' => 'red'],
                ['title' => 'Mid-term Supervisor Evaluation', 'desc' => 'Performance rating from HTE',                'done' => 110, 'total' => 124, 'tone' => 'amber'],
                ['title' => 'Monitoring Visit Form',          'desc' => 'Completed during coordinator visit',         'done' => 96,  'total' => 124, 'tone' => 'red'],
            ],
        ],
        'after' => [
            'label'    => 'After OJT duty',
            'subtitle' => 'Post-deployment completion',
            'percent'  => 82,
            'items'    => [
                ['title' => 'Final Narrative Report',        'desc' => 'Complete report with documentation', 'done' => 70,  'total' => 124, 'tone' => 'red'],
                ['title' => "Supervisor's Final Evaluation", 'desc' => 'Sealed by HTE supervisor',           'done' => 88,  'total' => 124, 'tone' => 'amber'],
                ['title' => 'Certificate of Completion',     'desc' => 'Issued by the host company',         'done' => 101, 'total' => 124, 'tone' => 'amber'],
                ['title' => 'Exit Survey',                   'desc' => 'Student feedback on the program',    'done' => 119, 'total' => 124, 'tone' => 'green'],
            ],
        ],
    ];

    $attentionStudents = [
        ['name' => 'Maria Santos',  'initials' => 'MS', 'missing' => 1, 'tone' => 'green'],
        ['name' => 'Joshua Lim',    'initials' => 'JL', 'missing' => 2, 'tone' => 'amber'],
        ['name' => 'Angela Reyes',  'initials' => 'AR', 'missing' => 3, 'tone' => 'blue'],
        ['name' => 'Carlo Mendoza', 'initials' => 'CM', 'missing' => 4, 'tone' => 'red'],
    ];
@endphp

<x-layouts.app title="Requirements">

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Compliance center</p>
            <h1 class="mt-2 text-4xl font-normal tracking-tight text-slate-900">OJT requirements</h1>
            <p class="mt-2 text-sm text-slate-500">Track every document and submission required before, during, and after OJT.</p>
        </div>

        <button type="button"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="{{ $icons['download'] }}" />
            </svg>
            Export compliance
        </button>
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

    {{-- Phase tabs (Alpine) --}}
    <div x-data="{ tab: 'before' }" class="mt-6">

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

            {{-- Requirement lists --}}
            <div>
                @foreach ($phases as $key => $phase)
                    <section x-show="tab === '{{ $key }}'" @if (! $loop->first) x-cloak @endif
                             class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <header class="flex items-center justify-between gap-4 border-b border-slate-100 p-6">
                            <div>
                                <h2 class="text-lg text-slate-900">{{ $phase['label'] }}</h2>
                                <p class="mt-1 text-xs text-slate-400">{{ $phase['subtitle'] }} &middot; {{ count($phase['items']) }} requirements</p>
                            </div>
                            <div class="w-36 text-right">
                                <p class="text-sm font-semibold text-slate-900">{{ $phase['percent'] }}% complete</p>
                                <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-blue-600" style="width: {{ $phase['percent'] }}%"></div>
                                </div>
                            </div>
                        </header>

                        <ul class="divide-y divide-slate-100">
                            @foreach ($phase['items'] as $item)
                                <li class="flex flex-wrap items-center gap-4 px-6 py-5 transition hover:bg-slate-50/70">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold {{ $tones[$item['tone']]['num'] }}">
                                        {{ $loop->iteration }}
                                    </span>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-slate-900">{{ $item['title'] }}</p>
                                        @if ($item['desc'])
                                            <p class="mt-1 text-xs leading-relaxed text-slate-400">{{ $item['desc'] }}</p>
                                        @endif
                                    </div>

                                    <div class="w-40 shrink-0 sm:mr-6">
                                        <div class="mb-2 flex justify-between text-[11px] text-slate-400">
                                            <span>Submitted</span>
                                            <span class="font-semibold text-slate-900">{{ $item['done'] }}/{{ $item['total'] }}</span>
                                        </div>
                                        <div class="h-1.5 overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full {{ $tones[$item['tone']]['bar'] }}"
                                                 style="width: {{ round(($item['done'] / $item['total']) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endforeach
            </div>

            {{-- Needs attention --}}
            <aside class="self-start rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg text-slate-900">Needs attention</h2>
                <p class="mt-1 text-xs text-slate-400">Students with missing requirements</p>

                <ul class="mt-4 divide-y divide-slate-50">
                    @foreach ($attentionStudents as $student)
                        <li class="flex cursor-pointer items-center gap-3 py-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $avatarTones[$student['tone']] }}">
                                {{ $student['initials'] }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-900">{{ $student['name'] }}</p>
                                <p class="text-[11px] text-slate-400">
                                    {{ $student['missing'] }} {{ $student['missing'] === 1 ? 'item' : 'items' }} missing
                                </p>
                            </div>
                            <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="{{ $icons['chevron'] }}" />
                            </svg>
                        </li>
                    @endforeach
                </ul>

                <button type="button"
                        class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    View all students
                </button>
            </aside>
        </div>
    </div>
</x-layouts.app>