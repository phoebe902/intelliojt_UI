@use('App\Enums\UserRole')

{{-- ============================================================
     STATIC DASHBOARD: every number below is sample data.
     The only things read from the app are the signed-in user's role and name.
     Local preview: add ?as=student (or administrator, coordinator,
     faculty_supervisor, company_supervisor) to the URL to see each role.
     ============================================================ --}}
@php
    // ---- 1. Who is looking ----
    $role = auth()->user()?->role;
    $role = $role instanceof UserRole ? $role : ($role ? UserRole::from($role) : UserRole::Coordinator);

    if (app()->environment('local') && request('as')) {
        $role = UserRole::tryFrom(request('as')) ?? $role;
    }

    $name     = explode(' ', trim(auth()->user()->name ?? 'Alex'))[0];
    $hour     = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');

    $periods = ['week' => 'This week', 'month' => 'This month', 'term' => 'This term'];
    $period  = request('period', 'month');

    // ---- 2. Sample data per role ----
    // mode: monitoring (admin, coordinator, faculty) | supervisor (company supervisor) | personal (student)
    $riskLevels = [
        'on_track' => ['label' => 'On track',  'dot' => 'bg-emerald-500', 'stroke' => 'stroke-emerald-500'],
        'medium'   => ['label' => 'Medium risk', 'dot' => 'bg-amber-400', 'stroke' => 'stroke-amber-400'],
        'high'     => ['label' => 'High risk',   'dot' => 'bg-rose-500',  'stroke' => 'stroke-rose-500'],
    ];

    $weeks = ['W1', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8'];
    $series = fn (array $scores) => array_map(fn ($l, $s) => ['label' => $l, 'score' => $s], $weeks, $scores);

    $emptyHint = 'No performance data yet.';

    $ui = match ($role) {
        UserRole::Administrator => [
            'mode'       => 'monitoring',
            'subtitle'   => "Here's the state of the OJT system today.",
            'perfTitle'  => 'Performance overview',
            'perfHint'   => 'Average intern performance across all programs',
            'riskTitle'  => 'Risk distribution',
            'riskHint'   => 'Current student risk levels',
            'totalLabel' => 'Total interns',
            'stats' => [
                ['label' => 'Total users',        'value' => 248, 'icon' => 'users',    'note' => '12 this month', 'good' => true,  'trend' => 'up'],
                ['label' => 'Partner companies',  'value' => 18,  'icon' => 'building', 'note' => '2 this month',  'good' => true,  'trend' => 'up'],
                ['label' => 'Active programs',    'value' => 6,   'icon' => 'document', 'note' => null,           'good' => true,  'trend' => 'up'],
                ['label' => 'Pending approvals',  'value' => 5,   'icon' => 'alert',    'note' => 'Needs review', 'good' => false, 'trend' => 'up'],
            ],
            'performance' => $series([68, 71, 70, 74, 76, 75, 79, 82]),
            'risk' => ['on_track' => 84, 'medium' => 19, 'high' => 9],
        ],
        UserRole::Coordinator => [
            'mode'       => 'monitoring',
            'subtitle'   => "Here's what's happening with your OJT program today.",
            'perfTitle'  => 'Performance overview',
            'perfHint'   => 'Average intern performance across key metrics',
            'riskTitle'  => 'Risk distribution',
            'riskHint'   => 'Current student risk levels',
            'totalLabel' => 'Total interns',
            'stats' => [
                ['label' => 'Total interns',         'value' => 112,   'icon' => 'users',    'note' => '8 this month',   'good' => true,  'trend' => 'up'],
                ['label' => 'Placed',                'value' => 96,    'icon' => 'building', 'note' => '86% of interns', 'good' => true,  'trend' => 'up'],
                ['label' => 'At risk',               'value' => 9,     'icon' => 'alert',    'note' => '3 more than last month', 'good' => false, 'trend' => 'up'],
                ['label' => 'Requirements complete', 'value' => '78%', 'icon' => 'check',    'note' => '5% this month',  'good' => true,  'trend' => 'up'],
            ],
            'performance' => $series([66, 70, 72, 71, 75, 77, 78, 81]),
            'risk' => ['on_track' => 84, 'medium' => 19, 'high' => 9],
        ],
        UserRole::FacultySupervisor => [
            'mode'       => 'monitoring',
            'subtitle'   => "Here's how your assigned students are doing.",
            'perfTitle'  => 'Assigned students’ performance',
            'perfHint'   => 'Average performance of the students you monitor',
            'riskTitle'  => 'Student risk levels',
            'riskHint'   => 'Based on hours, attendance and requirements',
            'totalLabel' => 'Assigned students',
            'stats' => [
                ['label' => 'Assigned students', 'value' => 24,    'icon' => 'users',    'note' => null,             'good' => true,  'trend' => 'up'],
                ['label' => 'On track',          'value' => 17,    'icon' => 'check',    'note' => '2 this month',   'good' => true,  'trend' => 'up'],
                ['label' => 'Needs follow-up',   'value' => 4,     'icon' => 'alert',    'note' => '1 more than last month', 'good' => false, 'trend' => 'up'],
                ['label' => 'Average progress',  'value' => '71%', 'icon' => 'clock',    'note' => '6% this month',  'good' => true,  'trend' => 'up'],
            ],
            'performance' => $series([62, 65, 69, 68, 72, 74, 73, 77]),
            'risk' => ['on_track' => 17, 'medium' => 3, 'high' => 4],
        ],
        UserRole::CompanySupervisor => [
            'mode'        => 'supervisor',
            'subtitle'    => "Here's what needs your attention with your interns today.",
            'todayTitle'  => 'Interns today',
            'todayHint'   => 'Attendance of the interns placed with you',
            'actionTitle' => 'Needs your action',
            'actionHint'  => 'Attendance to verify and evaluations due',
            'stats' => [
                ['label' => 'Your interns',          'value' => 6, 'icon' => 'users',    'note' => null,           'good' => true,  'trend' => 'up'],
                ['label' => 'Present today',         'value' => 4, 'icon' => 'check',    'note' => null,           'good' => true,  'trend' => 'up'],
                ['label' => 'Attendance to verify',  'value' => 7, 'icon' => 'clock',    'note' => 'Needs review', 'good' => false, 'trend' => 'up'],
                ['label' => 'Evaluations due',       'value' => 3, 'icon' => 'document', 'note' => 'Due Nov 20',   'good' => false, 'trend' => 'up'],
            ],
            'internsToday' => [
                ['name' => 'Maria Santos', 'program' => 'BSIT 4A',  'status' => 'present', 'time' => '8:02 AM'],
                ['name' => 'Daniel Cruz',  'program' => 'BSIT 4B',  'status' => 'present', 'time' => '7:55 AM'],
                ['name' => 'Sofia Lee',    'program' => 'BSCS 4A',  'status' => 'late',    'time' => '9:20 AM'],
                ['name' => 'Paolo Ramos',  'program' => 'BSCS 4B',  'status' => 'absent',  'time' => null],
                ['name' => 'Ana Reyes',    'program' => 'BSIT 4A',  'status' => 'present', 'time' => '8:10 AM'],
                ['name' => 'Luis Garcia',  'program' => 'BSCS 4A',  'status' => 'pending', 'time' => null],
            ],
            'actions' => [
                ['label' => 'Verify attendance',  'detail' => 'Logs waiting for your approval', 'count' => 7, 'href' => '#'],
                ['label' => 'Submit evaluations', 'detail' => 'Mid-term, due Nov 20',           'count' => 3, 'href' => '#'],
            ],
        ],
        UserRole::Student => [
            'mode'       => 'personal',
            'subtitle'   => "Here's how your training is going.",
            'perfTitle'  => 'My performance',
            'perfHint'   => 'Your performance across recent weeks',
            'sideTitle'  => 'Due soon',
            'sideHint'   => 'Requirements and deadlines coming up',
            'stats' => [
                ['label' => 'Hours rendered',  'value' => '364 / 500', 'icon' => 'clock',    'note' => '136 h to go',     'good' => true, 'trend' => 'up'],
                ['label' => 'Requirements',    'value' => '13 / 15',   'icon' => 'document', 'note' => null,              'good' => true, 'trend' => 'up'],
                ['label' => 'Attendance rate', 'value' => '92%',       'icon' => 'check',    'note' => '2% this month',   'good' => true, 'trend' => 'up'],
                ['label' => 'Notifications',   'value' => 3,           'icon' => 'bell',     'note' => null,              'good' => true, 'trend' => 'up'],
            ],
            'performance' => $series([72, 75, 74, 80, 78, 83, 85, 88]),
            'upcoming' => [
                ['title' => 'Mid-term evaluation',          'due' => 'Nov 20', 'urgent' => true,  'href' => '#'],
                ['title' => 'Weekly accomplishment report', 'due' => 'Nov 22', 'urgent' => false, 'href' => '#'],
                ['title' => '400-hour checkpoint',          'due' => 'Nov 27', 'urgent' => false, 'href' => '#'],
                ['title' => 'After-OJT requirements',       'due' => 'Dec 11', 'urgent' => false, 'href' => '#'],
            ],
        ],
    };

    $stats       = $ui['stats'];
    $performance = $ui['performance'] ?? [];
    $internsToday = $ui['internsToday'] ?? [];
    $actions     = $ui['actions'] ?? [];
    $upcoming    = $ui['upcoming'] ?? [];

    // Risk rows for the donut
    $risk  = [];
    $total = 0;
    foreach ($ui['risk'] ?? [] as $key => $count) {
        $risk[] = $riskLevels[$key] + ['count' => $count];
        $total += $count;
    }

    // ---- 3. Icons and colours ----
    $icons = [
        'users'    => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
        'building' => 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21',
        'alert'    => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
        'check'    => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'clock'    => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'document' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
        'bell'     => 'M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0',
        'up'       => 'M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18',
        'down'     => 'M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3',
    ];

    // Tailwind needs full class names, so these are written out literally
    $tones = [
        'users'    => 'bg-blue-50 text-blue-600',
        'building' => 'bg-violet-50 text-violet-600',
        'alert'    => 'bg-rose-50 text-rose-500',
        'check'    => 'bg-emerald-50 text-emerald-600',
        'clock'    => 'bg-amber-50 text-amber-600',
        'document' => 'bg-sky-50 text-sky-600',
        'bell'     => 'bg-violet-50 text-violet-600',
    ];

    $attendanceUi = [
        'present' => ['label' => 'Present',    'badge' => 'bg-emerald-50 text-emerald-700'],
        'late'    => ['label' => 'Late',       'badge' => 'bg-amber-50 text-amber-700'],
        'absent'  => ['label' => 'Absent',     'badge' => 'bg-rose-50 text-rose-700'],
        'pending' => ['label' => 'Not yet in', 'badge' => 'bg-slate-100 text-slate-500'],
    ];

    // ---- 4. Line chart geometry ----
    $W = 600; $H = 280;
    $padL = 40; $padR = 16; $padT = 12; $padB = 32;
    $cw = $W - $padL - $padR;
    $ch = $H - $padT - $padB;
    $n  = count($performance);

    $points = [];
    foreach ($performance as $i => $p) {
        $points[] = [
            'x'     => $padL + ($n > 1 ? $i * $cw / ($n - 1) : $cw / 2),
            'y'     => $padT + (1 - min(100, max(0, $p['score'])) / 100) * $ch,
            'label' => $p['label'],
            'score' => $p['score'],
        ];
    }

    $line = $area = '';
    if ($n > 1) {
        // Smooth curve using horizontal control points
        $line = 'M' . round($points[0]['x'], 1) . ',' . round($points[0]['y'], 1);
        for ($i = 1; $i < $n; $i++) {
            $mx = ($points[$i - 1]['x'] + $points[$i]['x']) / 2;
            $line .= ' C' . round($mx, 1) . ',' . round($points[$i - 1]['y'], 1)
                   . ' ' . round($mx, 1) . ',' . round($points[$i]['y'], 1)
                   . ' ' . round($points[$i]['x'], 1) . ',' . round($points[$i]['y'], 1);
        }
        $baseY = $padT + $ch;
        $area  = $line . ' L' . round($points[$n - 1]['x'], 1) . ',' . $baseY
               . ' L' . round($points[0]['x'], 1) . ',' . $baseY . ' Z';
    }

    // ---- 5. Donut geometry ----
    $radius = 80;
    $circ   = 2 * M_PI * $radius;
    $offset = 0;
@endphp

<x-layouts.app title="Overview">

    {{-- Greeting --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">
                {{ now()->format('l, F j') }} &middot; {{ $role->label() }}
            </p>
            <h1 class="mt-2 text-4xl font-normal tracking-tight text-slate-900">{{ $greeting }}, {{ $name }}.</h1>
            <p class="mt-2 text-sm text-slate-500">{{ $ui['subtitle'] }}</p>
        </div>

        <form method="GET">
            @if (request('as'))<input type="hidden" name="as" value="{{ request('as') }}">@endif
            <select name="period" onchange="this.form.submit()"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-3 pr-10 text-sm text-slate-600 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                @foreach ($periods as $value => $label)
                    <option value="{{ $value }}" @selected($period === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Stat cards --}}
    <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $s)
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $tones[$s['icon']] }}">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $icons[$s['icon']] }}" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">{{ $s['label'] }}</p>
                    <p class="text-3xl font-semibold leading-tight text-slate-900">{{ $s['value'] }}</p>

                    @if ($s['note'])
                        <p @class([
                            'mt-0.5 flex items-center gap-1 text-xs',
                            'text-emerald-600' => $s['good'],
                            'text-rose-500'    => ! $s['good'],
                        ])>
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="{{ $icons[$s['trend']] }}" />
                            </svg>
                            {{ $s['note'] }}
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if ($ui['mode'] === 'supervisor')

        {{-- ===== Company Supervisor: attendance + action items ===== --}}
        <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
                <h2 class="text-base font-semibold text-slate-900">{{ $ui['todayTitle'] }}</h2>
                <p class="mt-1 text-xs text-slate-400">{{ $ui['todayHint'] }}</p>

                <ul class="mt-4 divide-y divide-slate-100">
                    @forelse ($internsToday as $i)
                        <li class="flex items-center justify-between gap-3 py-3.5">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-900">{{ $i['name'] }}</p>
                                <p class="text-[11px] text-slate-400">{{ $i['program'] }}{{ $i['time'] ? ' · In at ' . $i['time'] : '' }}</p>
                            </div>
                            <span class="whitespace-nowrap rounded-full px-2.5 py-1 text-[10px] font-semibold {{ $attendanceUi[$i['status']]['badge'] }}">
                                {{ $attendanceUi[$i['status']]['label'] }}
                            </span>
                        </li>
                    @empty
                        <li class="py-16 text-center text-sm text-slate-400">No interns are scheduled today.</li>
                    @endforelse
                </ul>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">{{ $ui['actionTitle'] }}</h2>
                <p class="mt-1 text-xs text-slate-400">{{ $ui['actionHint'] }}</p>

                <ul class="mt-4 space-y-3">
                    @forelse ($actions as $a)
                        <li>
                            <a href="{{ $a['href'] }}" class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900">{{ $a['label'] }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $a['detail'] }}</p>
                                </div>
                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $a['count'] }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="py-10 text-center text-sm text-slate-400">You're all caught up.</li>
                    @endforelse
                </ul>
            </section>
        </div>

    @else

        {{-- ===== Monitoring roles and Student: chart + side panel ===== --}}
        <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">

            {{-- Performance --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
                <header class="flex items-start justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">{{ $ui['perfTitle'] }}</h2>
                        <p class="mt-1 text-xs text-slate-400">{{ $ui['perfHint'] }}</p>
                    </div>
                    <button type="button" class="text-slate-400 hover:text-slate-600" aria-label="More options">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="5" cy="12" r="1.6" /><circle cx="12" cy="12" r="1.6" /><circle cx="19" cy="12" r="1.6" />
                        </svg>
                    </button>
                </header>

                @if ($n === 0)
                    <p class="py-24 text-center text-sm text-slate-400">{{ $emptyHint }}</p>
                @else
                    <svg viewBox="0 0 {{ $W }} {{ $H }}" class="mt-6 h-auto w-full" role="img" aria-label="{{ $ui['perfTitle'] }} by week">
                        <defs>
                            <linearGradient id="perfFill" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#2563eb" stop-opacity="0.22" />
                                <stop offset="100%" stop-color="#2563eb" stop-opacity="0" />
                            </linearGradient>
                        </defs>

                        @foreach ([0, 25, 50, 75, 100] as $tick)
                            @php $gy = $padT + (1 - $tick / 100) * $ch; @endphp
                            <line x1="{{ $padL }}" x2="{{ $W - $padR }}" y1="{{ $gy }}" y2="{{ $gy }}"
                                  class="stroke-slate-200" stroke-dasharray="3 4" />
                            <text x="{{ $padL - 10 }}" y="{{ $gy + 4 }}" text-anchor="end" class="fill-slate-400" font-size="11">{{ $tick }}</text>
                        @endforeach

                        @if ($n > 1)
                            <path d="{{ $area }}" fill="url(#perfFill)" />
                            <path d="{{ $line }}" fill="none" class="stroke-blue-600" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        @endif

                        @foreach ($points as $pt)
                            <circle cx="{{ round($pt['x'], 1) }}" cy="{{ round($pt['y'], 1) }}" r="5" fill="#fff" class="stroke-blue-600" stroke-width="3">
                                <title>{{ $pt['label'] }}: {{ round($pt['score']) }}</title>
                            </circle>
                            <text x="{{ round($pt['x'], 1) }}" y="{{ $H - 8 }}" text-anchor="middle" class="fill-slate-400" font-size="11">{{ $pt['label'] }}</text>
                        @endforeach
                    </svg>
                @endif
            </section>

            @if ($ui['mode'] === 'monitoring')

                {{-- Risk distribution --}}
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <header class="flex items-start justify-between">
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">{{ $ui['riskTitle'] }}</h2>
                            <p class="mt-1 text-xs text-slate-400">{{ $ui['riskHint'] }}</p>
                        </div>
                        <button type="button" class="text-slate-400 hover:text-slate-600" aria-label="More options">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="5" cy="12" r="1.6" /><circle cx="12" cy="12" r="1.6" /><circle cx="19" cy="12" r="1.6" />
                            </svg>
                        </button>
                    </header>

                    <div class="relative mx-auto mt-6 h-52 w-52">
                        <svg viewBox="0 0 200 200" class="h-full w-full -rotate-90">
                            <circle cx="100" cy="100" r="{{ $radius }}" fill="none" class="stroke-slate-100" stroke-width="26" />
                            @foreach ($risk as $r)
                                @php $len = $total > 0 ? ($r['count'] / $total) * $circ : 0; @endphp
                                @if ($len > 0)
                                    <circle cx="100" cy="100" r="{{ $radius }}" fill="none"
                                            class="{{ $r['stroke'] }}" stroke-width="26"
                                            stroke-dasharray="{{ round($len, 2) }} {{ round($circ - $len, 2) }}"
                                            stroke-dashoffset="{{ -round($offset, 2) }}" />
                                @endif
                                @php $offset += $len; @endphp
                            @endforeach
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-semibold text-slate-900">{{ $total }}</span>
                            <span class="text-xs text-slate-400">{{ $ui['totalLabel'] }}</span>
                        </div>
                    </div>

                    <ul class="mt-6 space-y-3">
                        @foreach ($risk as $r)
                            <li class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2 text-slate-500">
                                    <span class="h-2.5 w-2.5 rounded-full {{ $r['dot'] }}"></span>
                                    {{ $r['label'] }}
                                </span>
                                <span class="font-semibold text-slate-900">
                                    {{ $r['count'] }}
                                    <span class="ml-1 text-xs font-normal text-slate-400">{{ $total ? number_format($r['count'] / $total * 100, 1) : 0 }}%</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </section>

            @else

                {{-- Student: due soon --}}
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-base font-semibold text-slate-900">{{ $ui['sideTitle'] }}</h2>
                    <p class="mt-1 text-xs text-slate-400">{{ $ui['sideHint'] }}</p>

                    <ul class="mt-4 space-y-3">
                        @forelse ($upcoming as $u)
                            <li>
                                <a href="{{ $u['href'] }}" class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50">
                                    <p class="min-w-0 text-sm font-semibold text-slate-900">{{ $u['title'] }}</p>
                                    <span class="whitespace-nowrap text-xs font-medium {{ $u['urgent'] ? 'text-rose-600' : 'text-slate-400' }}">{{ $u['due'] }}</span>
                                </a>
                            </li>
                        @empty
                            <li class="py-10 text-center text-sm text-slate-400">Nothing due soon.</li>
                        @endforelse
                    </ul>
                </section>

            @endif
        </div>

    @endif
</x-layouts.app>