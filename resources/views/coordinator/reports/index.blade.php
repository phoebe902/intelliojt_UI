@php
    $icons = [
        'document' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
        'building' => 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21',
        'clock'    => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'chart'    => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
        'eye'      => 'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
        'check'    => 'M4.5 12.75l6 6 9-13.5',
        'download' => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3',
        'chevron'  => 'M8.25 4.5l7.5 7.5-7.5 7.5',
    ];

    $snapshot = [
        'label'       => 'Semester snapshot',
        'title'       => 'OJT Program AY 2024–2025',
        'description' => '124 active trainees across 32 host companies with an overall requirement compliance rate of 89%.',
        'stats'       => [
            ['value' => '124', 'label' => 'Active trainees'],
            ['value' => '32',  'label' => 'Partner HTEs'],
            ['value' => '89%', 'label' => 'Compliance'],
            ['value' => '4.5', 'label' => 'Avg. HTE rating'],
        ],
    ];

    $reportCards = [
        ['title' => 'Requirements compliance', 'desc' => 'Before, during, and after OJT submission status by student.',   'tone' => 'bg-blue-50 text-blue-600',       'icon' => 'document'],
        ['title' => 'Placement summary',       'desc' => 'Student distribution, host companies, and available capacity.','tone' => 'bg-emerald-50 text-emerald-600', 'icon' => 'building'],
        ['title' => 'Rendered hours',          'desc' => 'DTR totals and progress against required training hours.',    'tone' => 'bg-violet-50 text-violet-600',   'icon' => 'clock'],
        ['title' => 'Weekly report status',    'desc' => 'Submission history and overdue weekly reports.',              'tone' => 'bg-amber-50 text-amber-600',     'icon' => 'chart'],
        ['title' => 'HTE performance',         'desc' => 'Company ratings, supervisor feedback, and evaluation trends.','tone' => 'bg-rose-50 text-rose-600',       'icon' => 'eye'],
        ['title' => 'Completion report',       'desc' => 'Final documents and completion eligibility by cohort.',       'tone' => 'bg-cyan-50 text-cyan-600',       'icon' => 'check'],
    ];
@endphp

<x-layouts.app title="Reports">

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Program intelligence</p>
            <h1 class="mt-2 text-4xl font-normal tracking-tight text-slate-900">Reports &amp; analytics</h1>
            <p class="mt-2 text-sm text-slate-500">Generate compliance, placement, and performance reports for your OJT program.</p>
        </div>

        <button type="button"
                class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700">
            Create custom report
        </button>
    </div>

    {{-- Semester snapshot --}}
    <section class="mt-8 rounded-3xl bg-[linear-gradient(135deg,#1d5cff_0%,#3b3fe0_55%,#4a2fd0_100%)] p-8 text-white shadow-xl shadow-indigo-600/25">
        <div class="flex flex-wrap items-start justify-between gap-6">
            <div class="max-w-xl">
                <p class="text-xs font-semibold uppercase tracking-wide text-white/80">{{ $snapshot['label'] }}</p>
                <h2 class="mt-3 text-3xl font-semibold">{{ $snapshot['title'] }}</h2>
                <p class="mt-3 text-sm leading-relaxed text-white/85">{{ $snapshot['description'] }}</p>
            </div>

            <button type="button"
                    class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-blue-600 transition hover:bg-blue-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="{{ $icons['download'] }}" />
                </svg>
                Download summary
            </button>
        </div>

        <dl class="mt-8 grid grid-cols-2 gap-6 border-t border-white/20 pt-7 md:grid-cols-4">
            @foreach ($snapshot['stats'] as $s)
                <div>
                    <dt class="text-2xl font-semibold">{{ $s['value'] }}</dt>
                    <dd class="mt-1 text-xs text-white/75">{{ $s['label'] }}</dd>
                </div>
            @endforeach
        </dl>
    </section>

    {{-- Standard reports --}}
    <div class="mt-10">
        <h2 class="text-xl font-semibold text-slate-900">Standard reports</h2>
        <p class="mt-1.5 text-xs text-slate-400">Generate an up-to-date report from current records</p>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($reportCards as $report)
            <article class="group flex cursor-pointer flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                <div class="flex items-start justify-between">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ $report['tone'] }}">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $icons[$report['icon']] }}" />
                        </svg>
                    </span>
                    <svg class="h-4 w-4 text-slate-300 transition group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $icons['chevron'] }}" />
                    </svg>
                </div>

                <h3 class="mt-6 text-base font-semibold text-slate-900">{{ $report['title'] }}</h3>
                <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">{{ $report['desc'] }}</p>

                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                    <span class="text-xs text-slate-400">Updated just now</span>
                    <button type="button" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $icons['download'] }}" />
                        </svg>
                        Generate
                    </button>
                </div>
            </article>
        @endforeach
    </div>
</x-layouts.app>