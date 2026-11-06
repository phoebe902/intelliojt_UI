{{-- ============================================================
     1. DATA + RISK CALCULATION
     Every ??= value below is a sample fallback: pass the real value
     from your controller and it takes over.
     ============================================================ --}}
@php
    $icons = [
        'check'    => 'M4.5 12.75l6 6 9-13.5',
        'calendar' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
        'alert'    => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
    ];

    // ---- Hours ----
    $required ??= 500;
    $rendered ??= 364;
    $percent  = min(100, round(($rendered / $required) * 100));
    $r        = 80;
    $circ     = 2 * M_PI * $r;
    $dash     = $circ * $percent / 100;

    $weeklyTarget = 40;
    $dailyHours   = 8;      // standard duty day
    $extendedHours = 10;    // longer duty day used in catch-up plans

    // Sample: a student who has been absent a lot lately
    $weeklyHours ??= [
        ['label' => 'W3',  'hours' => 32],
        ['label' => 'W4',  'hours' => 24],
        ['label' => 'W5',  'hours' => 20],
        ['label' => 'W6',  'hours' => 24],
        ['label' => 'W7',  'hours' => 16],
        ['label' => 'W8',  'hours' => 12],
        ['label' => 'W9',  'hours' => 16],
        ['label' => 'W10', 'hours' => 20],
    ];

    // ---- Dates and pace ----
    $today     = now()->startOfDay();
    $deadline  = \Carbon\Carbon::parse($deadline ?? '2026-12-18')->startOfDay();
    $remaining = max(0, $required - $rendered);
    $daysLeft  = max(0, (int) ceil($today->diffInDays($deadline, false)));
    $weeksLeft = $daysLeft / 7;

    $pace      = (float) (collect($weeklyHours)->take(-4)->avg('hours') ?? 0);   // avg h/week, last 4 weeks
    $projected = $rendered + $pace * $weeksLeft;                                  // hours by deadline at this pace
    $shortfall = max(0, (int) round($required - $projected));
    $coverage  = $remaining > 0 ? ($pace * $weeksLeft) / $remaining : 1;         // share of remaining hours the pace covers
    $paceFinish = ($remaining > 0 && $pace > 0)
        ? $today->copy()->addDays((int) ceil($remaining / ($pace / 7)))
        : null;
    $finishLate = $remaining > 0 && (! $paceFinish || $paceFinish->gt($deadline));

    $level = $remaining === 0 ? 'done' : ($coverage >= 1 ? 'on_track' : ($coverage >= 0.6 ? 'at_risk' : 'critical'));
    $isAtRisk = in_array($level, ['at_risk', 'critical'], true);

    $riskUi = [
        'done'     => ['label' => 'Completed',        'badge' => 'bg-emerald-50 text-emerald-700'],
        'on_track' => ['label' => 'On track',         'badge' => 'bg-emerald-50 text-emerald-700'],
        'at_risk'  => ['label' => 'At risk',          'badge' => 'bg-amber-50 text-amber-700'],
        'critical' => ['label' => 'Behind schedule',  'badge' => 'bg-rose-50 text-rose-700'],
    ][$level];

    // ---- Tasks still needed to complete OJT ----
    // threshold = hours that must be rendered before the task can be done / opened
    // early     = can be worked on during off-duty time, no hours needed
    $statusUi = [
        'todo'        => ['label' => 'Not started',      'badge' => 'bg-slate-100 text-slate-600'],
        'in_progress' => ['label' => 'In progress',      'badge' => 'bg-blue-50 text-blue-700'],
        'waiting'     => ['label' => 'Waiting on others', 'badge' => 'bg-violet-50 text-violet-700'],
        'done'        => ['label' => 'Done',             'badge' => 'bg-emerald-50 text-emerald-700'],
    ];

    $tasks = collect($tasks ?? [
        ['title' => 'Mid-term evaluation',          'note' => 'Your company supervisor is reviewing it',              'due' => '2026-11-20', 'status' => 'waiting',     'early' => false],
        ['title' => '400-hour checkpoint',          'note' => 'Render 400 hours in total',                            'due' => '2026-11-27', 'status' => 'in_progress', 'early' => false, 'threshold' => 400],
        ['title' => 'After-OJT requirements (0/4)', 'note' => '4 documents not yet submitted',                        'due' => '2026-12-11', 'status' => 'todo',        'early' => true],
        ['title' => 'Final narrative report',       'note' => 'Opens at 450 hours, but you can draft it now',         'due' => '2026-12-11', 'status' => 'todo',        'early' => true, 'threshold' => 450],
        ['title' => 'Complete 500 hours',           'note' => 'Needed for completion and your certificate',           'due' => '2026-12-18', 'status' => 'in_progress', 'early' => false, 'threshold' => 500],
    ])->map(function ($t) use ($today, $rendered, $pace) {
        $due = \Carbon\Carbon::parse($t['due'])->startOfDay();
        $eta = null;
        $lateDays = 0;
        $late = false;

        if (isset($t['threshold']) && $rendered < $t['threshold']) {
            if ($pace > 0) {
                $eta = $today->copy()->addDays((int) ceil(($t['threshold'] - $rendered) / ($pace / 7)));
                $lateDays = max(0, (int) round($due->diffInDays($eta, false)));
                $late = $lateDays > 0;
            } else {
                $late = true; // no recent hours at all
            }
        }

        return $t + ['dueAt' => $due, 'eta' => $eta, 'late' => $late, 'lateDays' => $lateDays];
    })->sortBy('dueAt')->values();

    $offDutyTasks = $tasks->where('early', true)->where('status', '!=', 'done');

    // ---- Catch-up plans (only built when at risk) ----
    $plans = [];
    $recommended = null;

    if ($isAtRisk) {
        $neededPerWeek = $remaining / max($weeksLeft, 0.14);
        $need          = $neededPerWeek * 1.1;                                  // 10% buffer for absences
        $typicalDays   = max(1, min(5, (int) round($pace / $dailyHours)));      // weekdays attended lately

        $defs = [
            ['key' => 'standard', 'title' => 'Attend more days',  'blurb' => 'Keep 8-hour days and show up on more of your scheduled weekdays.',  'weekdays' => (int) ceil($need / $dailyHours),    'weekend' => 0, 'hours' => $dailyHours],
            ['key' => 'longer',   'title' => 'Longer duty days',  'blurb' => "Render {$extendedHours} hours instead of {$dailyHours} on the days you attend.", 'weekdays' => (int) ceil($need / $extendedHours), 'weekend' => 0, 'hours' => $extendedHours],
            ['key' => 'weekend',  'title' => 'Add weekend duty',  'blurb' => 'Keep your usual weekdays and cover the gap with weekend duty.',     'weekdays' => $typicalDays, 'weekend' => (int) max(1, ceil(max($need - $typicalDays * $dailyHours, 0) / $dailyHours)), 'hours' => $dailyHours],
        ];

        foreach ($defs as $d) {
            $weekly   = ($d['weekdays'] + $d['weekend']) * $d['hours'];
            $feasible = $d['weekdays'] <= 5 && $d['weekend'] <= 2 && $weekly > 0;
            $finish   = $feasible ? $today->copy()->addDays((int) ceil(($remaining / $weekly) * 7)) : null;

            $schedule = $d['weekdays'] . ' weekday' . ($d['weekdays'] > 1 ? 's' : '') . ' × ' . $d['hours'] . ' h';
            if ($d['weekend']) {
                $schedule .= ' + ' . ($d['weekend'] === 1 ? 'Saturday' : 'Sat & Sun') . ' × ' . $d['hours'] . ' h';
            }

            $plans[] = $d + [
                'weekly'    => $weekly,
                'feasible'  => $feasible,
                'finish'    => $finish,
                'daysEarly' => $finish ? max(0, (int) round($finish->diffInDays($deadline, false))) : 0,
                'schedule'  => $schedule,
            ];
        }

        $recommended = collect($plans)->firstWhere('feasible', true)['key'] ?? null;
    }
@endphp

<x-layouts.app title="My progress">

    {{-- ============================================================
         2. HEADER
         ============================================================ --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Training overview</p>
            <h1 class="mt-2 text-4xl font-normal tracking-tight text-slate-900">My progress</h1>
            <p class="mt-2 text-sm text-slate-500">See how close you are to completing your OJT hours, requirements and evaluations.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-sm">
            <p class="text-xs text-slate-400">Host company</p>
            <p class="text-sm font-semibold text-slate-900">TechCore Solutions</p>
            <p class="text-[11px] text-slate-400">Supervisor: Ramon Diaz</p>
        </div>
    </div>

    {{-- ============================================================
         3. AT-RISK BANNER
         ============================================================ --}}
    @if ($isAtRisk)
        <div class="mt-6 flex flex-wrap items-center justify-between gap-4 rounded-2xl border p-5
                    {{ $level === 'critical' ? 'border-rose-200 bg-rose-50' : 'border-amber-200 bg-amber-50' }}">
            <div class="flex items-start gap-3">
                <svg class="mt-0.5 h-6 w-6 shrink-0 {{ $level === 'critical' ? 'text-rose-600' : 'text-amber-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="{{ $icons['alert'] }}" />
                </svg>
                <div>
                    <p class="text-sm font-semibold {{ $level === 'critical' ? 'text-rose-900' : 'text-amber-900' }}">
                        {{ $level === 'critical' ? 'You are behind schedule' : 'You may not finish on time' }}
                    </p>
                    <p class="mt-1 text-sm {{ $level === 'critical' ? 'text-rose-800' : 'text-amber-800' }}">
                        At your recent pace of about {{ round($pace) }} h per week, you would reach roughly {{ round($projected) }} of {{ $required }} hours by {{ $deadline->format('M j') }},
                        which is {{ $shortfall }} h short. You need about {{ ceil($remaining / max($weeksLeft, 0.14)) }} h per week to finish on time.
                    </p>
                </div>
            </div>

            <a href="#catch-up"
               class="rounded-xl bg-white px-4 py-2.5 text-sm font-semibold shadow-sm transition hover:bg-slate-50 {{ $level === 'critical' ? 'text-rose-700' : 'text-amber-700' }}">
                See catch-up plan
            </a>
        </div>
    @endif

    {{-- ============================================================
         4. HOURS RING + WEEKLY CHART
         ============================================================ --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-[1fr_2fr]">

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg text-slate-900">Rendered hours</h2>
            <p class="mt-1 text-xs text-slate-400">Against the {{ $required }}-hour target</p>

            <div class="relative mx-auto mt-6 h-52 w-52">
                <svg viewBox="0 0 200 200" class="h-full w-full -rotate-90">
                    <circle cx="100" cy="100" r="{{ $r }}" fill="none" class="stroke-slate-100" stroke-width="18" />
                    <circle cx="100" cy="100" r="{{ $r }}" fill="none" class="stroke-blue-600" stroke-width="18"
                            stroke-linecap="round"
                            stroke-dasharray="{{ round($dash, 2) }} {{ round($circ - $dash, 2) }}" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-4xl font-semibold text-slate-900">{{ $percent }}%</span>
                    <span class="text-[10px] text-slate-400">{{ $rendered }} of {{ $required }} h</span>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3 rounded-xl p-4 {{ $finishLate ? 'bg-rose-50' : 'bg-blue-50' }}">
                <svg class="h-6 w-6 {{ $finishLate ? 'text-rose-600' : 'text-blue-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="{{ $icons['calendar'] }}" />
                </svg>
                <div>
                    <p class="text-[10px] text-slate-400">Estimated completion at current pace</p>
                    <p class="text-sm font-semibold text-slate-800">
                        @if ($remaining === 0)
                            Completed
                        @else
                            {{ $paceFinish ? $paceFinish->format('F j, Y') : 'Not enough recent hours' }}
                        @endif
                    </p>
                    <p class="text-[11px] {{ $finishLate ? 'font-medium text-rose-600' : 'text-slate-400' }}">Required by {{ $deadline->format('F j, Y') }}</p>
                </div>
            </div>

            <p class="mt-4 text-center text-xs text-slate-400">{{ $remaining }} hours remaining</p>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-lg text-slate-900">Weekly hours</h2>
                    <p class="mt-1 text-xs text-slate-400">
                        Last {{ count($weeklyHours) }} weeks &middot; target {{ $weeklyTarget }} h per week &middot; recent average {{ round($pace) }} h
                    </p>
                </div>
                <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $riskUi['badge'] }}">{{ $riskUi['label'] }}</span>
            </div>

            <div class="mt-8 flex h-56 items-end justify-between gap-3">
                @foreach ($weeklyHours as $w)
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <span class="text-[11px] font-semibold text-slate-600">{{ $w['hours'] }}</span>
                        <div class="flex h-44 w-full items-end rounded-xl bg-slate-100">
                            <div class="w-full rounded-xl {{ $w['hours'] >= $weeklyTarget ? 'bg-blue-600' : 'bg-blue-300' }}"
                                 style="height: {{ min(100, ($w['hours'] / $weeklyTarget) * 100) }}%"></div>
                        </div>
                        <span class="text-[11px] text-slate-400">{{ $w['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    {{-- ============================================================
         5. TASKS TO COMPLETE
         ============================================================ --}}
    <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg text-slate-900">Tasks to complete</h2>
        <p class="mt-1 text-xs text-slate-400">What you still need to finish, by due date</p>

        <ul class="mt-4 divide-y divide-slate-100">
            @foreach ($tasks as $t)
                <li class="flex flex-wrap items-center justify-between gap-3 py-4">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900">{{ $t['title'] }}</p>
                        <p class="mt-0.5 text-xs text-slate-400">{{ $t['note'] }}</p>

                        @if (isset($t['threshold']) && $rendered < $t['threshold'])
                            <p class="mt-1 text-xs font-medium {{ $t['late'] ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ $t['threshold'] - $rendered }} h to go &middot;
                                @if (! $t['eta'])
                                    no recent hours, so this will not be reached at your current pace
                                @else
                                    at your pace: {{ $t['eta']->format('M j') }}
                                    {{ $t['late'] ? '(' . $t['lateDays'] . ' day' . ($t['lateDays'] > 1 ? 's' : '') . ' late)' : '(on pace)' }}
                                @endif
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-500">Due {{ $t['dueAt']->format('M j') }}</span>
                        <span class="whitespace-nowrap rounded-full px-2.5 py-1 text-[10px] font-semibold {{ $statusUi[$t['status']]['badge'] }}">
                            {{ $statusUi[$t['status']]['label'] }}
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    </section>

    {{-- ============================================================
         6. CATCH-UP PLAN (only when at risk)
         ============================================================ --}}
    @if ($isAtRisk)
        <section id="catch-up" x-data="catchUp" class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg text-slate-900">Catch-up plan</h2>
                    <p class="mt-1 text-xs text-slate-400">
                        Ways to render the remaining {{ $remaining }} hours before {{ $deadline->format('M j') }}. Each plan includes a 10% buffer for missed days.
                    </p>
                </div>
                <p class="text-xs text-slate-500">
                    Needed: <span class="font-semibold text-slate-800">{{ ceil($neededPerWeek) }} h/week</span>
                    &middot; Now: <span class="font-semibold text-slate-800">{{ round($pace) }} h/week</span>
                </p>
            </div>

            <div class="mt-5 grid gap-4 md:grid-cols-3">
                @foreach ($plans as $p)
                    <button type="button" @disabled(! $p['feasible'])
                            @click="selected = '{{ $p['key'] }}'"
                            :class="selected === '{{ $p['key'] }}' ? 'border-blue-600 bg-blue-50/60 ring-2 ring-blue-600/20' : 'border-slate-200 hover:bg-slate-50'"
                            class="flex flex-col rounded-2xl border p-5 text-left transition disabled:cursor-not-allowed disabled:opacity-50">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-semibold text-slate-900">{{ $p['title'] }}</p>
                            @if ($p['key'] === $recommended)
                                <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">Recommended</span>
                            @endif
                        </div>
                        <p class="mt-1 text-xs text-slate-500">{{ $p['blurb'] }}</p>

                        <dl class="mt-4 space-y-1.5 text-xs">
                            <div class="flex justify-between gap-3"><dt class="text-slate-400">Schedule</dt><dd class="text-right font-semibold text-slate-800">{{ $p['schedule'] }}</dd></div>
                            <div class="flex justify-between gap-3"><dt class="text-slate-400">Per week</dt><dd class="font-semibold text-slate-800">{{ $p['weekly'] }} h</dd></div>
                            <div class="flex justify-between gap-3">
                                <dt class="text-slate-400">Finish</dt>
                                <dd class="font-semibold {{ $p['feasible'] ? 'text-emerald-600' : 'text-rose-600' }}">
                                    @if ($p['feasible'])
                                        {{ $p['finish']->format('M j') }}{{ $p['daysEarly'] ? ' (' . $p['daysEarly'] . ' d early)' : '' }}
                                    @else
                                        Not realistic
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </button>
                @endforeach
            </div>

            {{-- Rearranged order: work that doesn't need duty hours --}}
            @if ($offDutyTasks->isNotEmpty())
                <div class="mt-5 rounded-xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-900">Do these off duty</p>
                    <p class="mt-1 text-xs text-slate-500">These don't depend on your hours, so start them now and let duty hours be your only bottleneck.</p>
                    <ul class="mt-3 space-y-1.5 text-xs text-slate-700">
                        @foreach ($offDutyTasks as $t)
                            <li class="flex justify-between gap-3">
                                <span>{{ $t['title'] }}</span>
                                <span class="text-slate-400">Due {{ $t['dueAt']->format('M j') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5">
                <p class="text-xs" :class="sent ? 'text-emerald-600' : 'text-slate-400'"
                   x-text="sent ? 'Plan sent. Your coordinator and company supervisor will review it.' : 'Extra-long days and weekend duty may need approval from your company supervisor and OJT coordinator.'"></p>

                <button type="button" @click="send()" :disabled="!selected || sent || sending"
                        class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-40">
                    Send plan for approval
                </button>
            </div>
        </section>
    @endif

    {{-- ============================================================
         7. MILESTONES + REQUIREMENTS + FEEDBACK
         ============================================================ --}}
    @php
        // state: done | current | upcoming
        $milestones ??= [
            ['title' => 'Pre-deployment requirements', 'note' => 'Completed Jan 10',       'state' => 'done'],
            ['title' => 'Orientation seminar (PDOS)',  'note' => 'Completed Jan 12',       'state' => 'done'],
            ['title' => 'Start of OJT',                'note' => 'Started Jan 15, 2026',   'state' => 'done'],
            ['title' => 'Mid-term evaluation',         'note' => 'Under review',           'state' => 'current'],
            ['title' => '400-hour checkpoint',         'note' => '36 hours to go',         'state' => 'upcoming'],
            ['title' => 'Final narrative report',      'note' => 'Opens at 450 hours',     'state' => 'upcoming'],
            ['title' => 'Completion and certificate',  'note' => 'Estimated Dec 18, 2026', 'state' => 'upcoming'],
        ];

        $compliance ??= [
            ['label' => 'Before OJT duty', 'done' => 6, 'total' => 7, 'bar' => 'bg-emerald-500'],
            ['label' => 'During OJT duty', 'done' => 3, 'total' => 4, 'bar' => 'bg-blue-600'],
            ['label' => 'After OJT duty',  'done' => 0, 'total' => 4, 'bar' => 'bg-slate-300'],
        ];

        $scores ??= [
            ['label' => 'Technical skills', 'score' => 4.6],
            ['label' => 'Work attitude',    'score' => 4.8],
            ['label' => 'Communication',    'score' => 4.2],
            ['label' => 'Punctuality',      'score' => 4.4],
        ];
    @endphp

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Milestones --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg text-slate-900">Milestones</h2>
            <p class="mt-1 text-xs text-slate-400">Your path from deployment to completion</p>

            <ol class="mt-6">
                @foreach ($milestones as $m)
                    <li class="relative flex gap-4 pb-6 last:pb-0">
                        @unless ($loop->last)
                            <span class="absolute left-[15px] top-8 h-full w-px {{ $m['state'] === 'done' ? 'bg-emerald-200' : 'bg-slate-200' }}"></span>
                        @endunless

                        @if ($m['state'] === 'done')
                            <span class="z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="{{ $icons['check'] }}" />
                                </svg>
                            </span>
                        @elseif ($m['state'] === 'current')
                            <span class="z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-blue-600 bg-blue-50">
                                <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                            </span>
                        @else
                            <span class="z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-slate-200 bg-white"></span>
                        @endif

                        <div class="pt-0.5">
                            <p class="text-sm font-semibold {{ $m['state'] === 'upcoming' ? 'text-slate-400' : 'text-slate-900' }}">{{ $m['title'] }}</p>
                            <p class="mt-0.5 text-xs {{ $m['state'] === 'current' ? 'font-medium text-blue-600' : 'text-slate-400' }}">{{ $m['note'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        <div class="space-y-6">

            {{-- Requirements compliance --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg text-slate-900">Requirements</h2>
                <p class="mt-1 text-xs text-slate-400">Documents submitted per phase</p>

                <ul class="mt-5 space-y-4">
                    @foreach ($compliance as $c)
                        <li>
                            <div class="mb-2 flex justify-between text-xs">
                                <span class="text-slate-500">{{ $c['label'] }}</span>
                                <span class="font-semibold text-slate-800">{{ $c['done'] }}/{{ $c['total'] }}</span>
                            </div>
                            <div class="h-1.5 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $c['bar'] }}" style="width: {{ round(($c['done'] / $c['total']) * 100) }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>

            {{-- Supervisor evaluation --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg text-slate-900">Supervisor feedback</h2>
                        <p class="mt-1 text-xs text-slate-400">Latest evaluation &middot; Ramon Diaz</p>
                    </div>
                    <p class="text-2xl font-semibold text-blue-600">{{ number_format(collect($scores)->avg('score'), 1) }}<span class="text-xs font-normal text-slate-400"> / 5</span></p>
                </div>

                <ul class="mt-5 grid grid-cols-2 gap-4">
                    @foreach ($scores as $s)
                        <li class="rounded-xl bg-slate-50 p-3.5">
                            <p class="text-[11px] text-slate-400">{{ $s['label'] }}</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ number_format($s['score'], 1) }}</p>
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>
    </div>

    {{-- ============================================================
         8. ALPINE (catch-up plan selection)
         ============================================================ --}}
    @if ($isAtRisk)
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('catchUp', () => ({
                    selected: @json($recommended),
                    sent: false,
                    sending: false,
                    async send() {
                        if (!this.selected) return;
                        this.sending = true;
                        try {
                            // Set $catchUpUrl in your controller to save the request; without it this only updates the UI.
                            const url = @json($catchUpUrl ?? null);
                            if (url) {
                                const res = await fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '',
                                    },
                                    body: JSON.stringify({ plan: this.selected }),
                                });
                                if (!res.ok) throw new Error('Request failed');
                            }
                            this.sent = true;
                        } catch (e) {
                            alert('Could not send the plan. Please try again.');
                        } finally {
                            this.sending = false;
                        }
                    },
                }));
            });
        </script>
    @endif
</x-layouts.app>