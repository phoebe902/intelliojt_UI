@php
    $icons = [
        'clock'    => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'calendar' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
        'check'    => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'alert'    => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-4.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
    ];

    $stats = [
        ['icon' => 'clock',    'tone' => 'bg-blue-50 text-blue-600',       'value' => '364 h', 'label' => 'Hours rendered', 'note' => 'of 500 required'],
        ['icon' => 'calendar', 'tone' => 'bg-violet-50 text-violet-600',   'value' => '32 h',  'label' => 'This week',      'note' => 'Target: 40 h'],
        ['icon' => 'check',    'tone' => 'bg-emerald-50 text-emerald-600', 'value' => '96%',   'label' => 'Attendance rate','note' => '45 of 47 days present'],
        ['icon' => 'alert',    'tone' => 'bg-amber-50 text-amber-600',     'value' => '2',     'label' => 'Late arrivals',  'note' => 'This semester'],
    ];

    $statusStyles = [
        'Present' => 'bg-emerald-50 text-emerald-700',
        'Late'    => 'bg-amber-50 text-amber-700',
        'Absent'  => 'bg-rose-50 text-rose-700',
    ];

    $logs = [
        ['date' => 'Fri, Nov 06', 'in' => '8:02 AM',  'out' => '5:04 PM', 'hours' => '8.0', 'status' => 'Present', 'verified' => false],
        ['date' => 'Thu, Nov 05', 'in' => '7:58 AM',  'out' => '5:01 PM', 'hours' => '8.0', 'status' => 'Present', 'verified' => true],
        ['date' => 'Wed, Nov 04', 'in' => '8:21 AM',  'out' => '5:10 PM', 'hours' => '8.0', 'status' => 'Late',    'verified' => true],
        ['date' => 'Tue, Nov 03', 'in' => '8:00 AM',  'out' => '5:02 PM', 'hours' => '8.0', 'status' => 'Present', 'verified' => true],
        ['date' => 'Mon, Nov 02', 'in' => '—',        'out' => '—',       'hours' => '0.0', 'status' => 'Absent',  'verified' => true],
        ['date' => 'Fri, Oct 30', 'in' => '8:05 AM',  'out' => '5:00 PM', 'hours' => '8.0', 'status' => 'Present', 'verified' => true],
        ['date' => 'Thu, Oct 29', 'in' => '7:55 AM',  'out' => '5:03 PM', 'hours' => '8.0', 'status' => 'Present', 'verified' => true],
        ['date' => 'Wed, Oct 28', 'in' => '8:00 AM',  'out' => '5:00 PM', 'hours' => '8.0', 'status' => 'Present', 'verified' => true],
    ];

    $week = [
        ['day' => 'Mon', 'hours' => 0],
        ['day' => 'Tue', 'hours' => 8],
        ['day' => 'Wed', 'hours' => 8],
        ['day' => 'Thu', 'hours' => 8],
        ['day' => 'Fri', 'hours' => 8],
    ];
@endphp

<x-layouts.app title="Attendance">

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Daily time record</p>
            <h1 class="mt-2 text-4xl font-normal tracking-tight text-slate-900">Attendance</h1>
            <p class="mt-2 text-sm text-slate-500">Clock in and out each day. Your supervisor verifies every entry.</p>
        </div>

        <button type="button"
                class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
            Request correction
        </button>
    </div>

    {{-- Stat cards --}}
    <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
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

        {{-- Attendance log --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <header class="flex items-start justify-between border-b border-slate-100 p-6">
                <div>
                    <h2 class="text-lg text-slate-900">Attendance log</h2>
                    <p class="mt-1 text-xs text-slate-400">Your most recent daily records</p>
                </div>
                <button type="button" class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                    Filter month
                </button>
            </header>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left">
                    <thead class="bg-slate-50/70">
                        <tr class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="px-6 py-3.5">Date</th>
                            <th class="px-6 py-3.5">Time in</th>
                            <th class="px-6 py-3.5">Time out</th>
                            <th class="px-6 py-3.5">Hours</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5">Supervisor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($logs as $log)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $log['date'] }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $log['in'] }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $log['out'] }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $log['hours'] }}</td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $statusStyles[$log['status']] }}">{{ $log['status'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs {{ $log['verified'] ? 'font-medium text-emerald-600' : 'text-slate-400' }}">
                                    {{ $log['verified'] ? 'Verified' : 'Awaiting' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <aside class="space-y-6 self-start">

            {{-- Clock in / out --}}
            <section x-data="attendanceClock" class="rounded-2xl bg-slate-900 p-6 text-white shadow-xl shadow-slate-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-300">Today &middot; Fri, Nov 06</p>
                <p class="mt-4 text-4xl font-semibold tabular-nums" x-text="now">--:--</p>

                <p class="mt-2 text-sm" :class="on ? 'text-emerald-400' : 'text-slate-400'">
                    <span x-show="!on && !inAt">You have not clocked in yet.</span>
                    <span x-show="on" x-cloak>Clocked in at <span x-text="inAt"></span></span>
                    <span x-show="!on && inAt" x-cloak>Clocked out at <span x-text="outAt"></span></span>
                </p>

                <button type="button" @click="toggle()"
                        :class="on ? 'bg-rose-500 hover:bg-rose-600 shadow-rose-500/30' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-600/30'"
                        class="mt-6 w-full rounded-xl px-5 py-3.5 text-sm font-semibold text-white shadow-lg transition">
                    <span x-text="on ? 'Clock out' : 'Clock in'">Clock in</span>
                </button>

                <p class="mt-3 text-center text-[11px] text-slate-500">Host company: TechCore Solutions</p>
            </section>

            {{-- This week --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg text-slate-900">This week</h2>
                <p class="mt-1 text-xs text-slate-400">Hours rendered per day</p>

                <div class="mt-5 flex h-32 items-end justify-between gap-3">
                    @foreach ($week as $d)
                        <div class="flex flex-1 flex-col items-center gap-2">
                            <div class="flex h-24 w-full items-end rounded-lg bg-slate-100">
                                <div class="w-full rounded-lg {{ $d['hours'] ? 'bg-blue-600' : 'bg-transparent' }}"
                                     style="height: {{ ($d['hours'] / 8) * 100 }}%"></div>
                            </div>
                            <span class="text-[11px] text-slate-400">{{ $d['day'] }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
        </aside>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('attendanceClock', () => ({
                on: false,
                inAt: '',
                outAt: '',
                now: '',
                init() {
                    this.tick();
                    setInterval(() => this.tick(), 1000);
                },
                tick() {
                    this.now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                },
                stamp() {
                    return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                },
                toggle() {
                    if (!this.on) {
                        this.on = true;
                        this.inAt = this.stamp();
                        this.outAt = '';
                    } else {
                        this.on = false;
                        this.outAt = this.stamp();
                    }
                },
            }));
        });
    </script>
</x-layouts.app>