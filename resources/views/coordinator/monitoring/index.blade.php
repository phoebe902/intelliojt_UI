@php
    $icons = [
        'eye'     => 'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
        'clock'   => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'document'=> 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
        'chevron' => 'M8.25 4.5l7.5 7.5-7.5 7.5',
    ];

    $targetHours = 500;

    $monitorStats = [
        ['icon' => 'eye',      'tone' => 'bg-blue-50 text-blue-600',       'value' => '124',   'label' => 'Active trainees',  'note' => 'Current semester'],
        ['icon' => 'clock',    'tone' => 'bg-emerald-50 text-emerald-600', 'value' => '4,286', 'label' => 'Hours this week',  'note' => '+8.4% from last week'],
        ['icon' => 'document', 'tone' => 'bg-amber-50 text-amber-600',     'value' => '9',     'label' => 'Missing reports',  'note' => 'Due this Wednesday'],
        ['icon' => 'eye',      'tone' => 'bg-rose-50 text-rose-600',       'value' => '3',     'label' => 'At-risk students', 'note' => 'Require intervention'],
    ];

    $avatarTones = [
        'green' => 'bg-emerald-50 text-emerald-700',
        'amber' => 'bg-amber-50 text-amber-700',
        'blue'  => 'bg-blue-50 text-blue-700',
        'red'   => 'bg-rose-50 text-rose-700',
    ];

    $trainees = [
        ['name' => 'Maria Santos',   'initials' => 'MS', 'avatar' => 'green', 'company' => 'TechCore Solutions', 'hours' => 364, 'status' => 'Active'],
        ['name' => 'Joshua Lim',     'initials' => 'JL', 'avatar' => 'amber', 'company' => 'Cloudline Labs',     'hours' => 287, 'status' => 'Needs attention'],
        ['name' => 'Angela Reyes',   'initials' => 'AR', 'avatar' => 'blue',  'company' => 'Northpeak Digital',  'hours' => 421, 'status' => 'Active'],
        ['name' => 'Carlo Mendoza',  'initials' => 'CM', 'avatar' => 'red',   'company' => 'Vertex Systems',     'hours' => 198, 'status' => 'Needs attention'],
        ['name' => 'Patricia Gomez', 'initials' => 'PG', 'avatar' => 'red',   'company' => 'Brightlane Tech',    'hours' => 176, 'status' => 'Needs attention'],
    ];

    $interventions = [
        ['name' => 'Carlo Mendoza',  'initials' => 'CM', 'reason' => 'Hours behind schedule'],
        ['name' => 'Joshua Lim',     'initials' => 'JL', 'reason' => '2 reports overdue'],
        ['name' => 'Patricia Gomez', 'initials' => 'PG', 'reason' => 'Low supervisor feedback'],
    ];
@endphp

<x-layouts.app title="Monitoring">

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Live progress</p>
            <h1 class="mt-2 text-4xl font-normal tracking-tight text-slate-900">Student monitoring</h1>
            <p class="mt-2 text-sm text-slate-500">Monitor rendered hours, weekly activity, and students who need intervention.</p>
        </div>

        <button type="button"
                class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
            Set monitoring period
        </button>
    </div>

    {{-- Stat cards --}}
    <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($monitorStats as $stat)
            <div class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div>
                    <p class="text-xs text-slate-500">{{ $stat['label'] }}</p>
                    <p class="mt-2 text-3xl font-medium text-slate-900">{{ $stat['value'] }}</p>
                    <p class="mt-1 text-[11px] text-slate-400">{{ $stat['note'] }}</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $stat['tone'] }}">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $icons[$stat['icon']] }}" />
                    </svg>
                </span>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-[1fr_340px]">

        {{-- Training progress --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <header class="border-b border-slate-100 p-6">
                <h2 class="text-lg text-slate-900">Training progress</h2>
                <p class="mt-1 text-xs text-slate-400">Rendered hours against the {{ $targetHours }}-hour target</p>
            </header>

            <ul class="divide-y divide-slate-100">
                @foreach ($trainees as $t)
                    @php $needsAttention = $t['status'] === 'Needs attention'; @endphp
                    <li class="flex gap-4 px-6 py-5 transition hover:bg-slate-50/70">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $avatarTones[$t['avatar']] }}">
                            {{ $t['initials'] }}
                        </span>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $t['name'] }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ $t['company'] }}</p>
                                </div>
                                <span class="whitespace-nowrap rounded-full px-3 py-1 text-[11px] font-semibold
                                    {{ $needsAttention ? 'bg-slate-100 text-slate-600' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $t['status'] }}
                                </span>
                            </div>

                            <div class="mt-3 flex items-center gap-5">
                                <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full {{ $needsAttention ? 'bg-amber-500' : 'bg-blue-600' }}"
                                         style="width: {{ round(($t['hours'] / $targetHours) * 100) }}%"></div>
                                </div>
                                <span class="w-20 text-right text-xs font-semibold text-slate-700">{{ $t['hours'] }} / {{ $targetHours }}h</span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- Early intervention --}}
        <aside class="self-start rounded-2xl bg-slate-900 p-6 text-white shadow-xl shadow-slate-900/20">
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-300">Early intervention</p>
            <h2 class="mt-3 text-2xl font-semibold">{{ count($interventions) }} students need attention</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-400">
                Based on low rendered hours, missing weekly reports, and recent supervisor feedback.
            </p>

            <ul class="mt-5 space-y-3">
                @foreach ($interventions as $i)
                    <li class="flex cursor-pointer items-center gap-3 rounded-2xl bg-slate-800 p-3.5 transition hover:bg-slate-700">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-950 text-xs font-bold text-rose-300">
                            {{ $i['initials'] }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold">{{ $i['name'] }}</p>
                            <p class="text-xs text-slate-400">{{ $i['reason'] }}</p>
                        </div>
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $icons['chevron'] }}" />
                        </svg>
                    </li>
                @endforeach
            </ul>
        </aside>
    </div>
</x-layouts.app>