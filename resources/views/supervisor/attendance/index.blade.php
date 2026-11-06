@php
    $icons = [
        'clock'    => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'check'    => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'alert'    => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-4.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
        'students' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
    ];

    $avatarTones = [
        'green' => 'bg-emerald-50 text-emerald-700',
        'amber' => 'bg-amber-50 text-amber-700',
        'blue'  => 'bg-blue-50 text-blue-700',
        'red'   => 'bg-rose-50 text-rose-700',
    ];

    $entries = [
        ['id' => 'e1', 'name' => 'Maria Santos',  'initials' => 'MS', 'tone' => 'green', 'program' => 'BSIT 4A', 'date' => 'Fri, Nov 06', 'in' => '8:02 AM', 'out' => '5:04 PM', 'hours' => '8.0', 'status' => 'Present'],
        ['id' => 'e2', 'name' => 'Daniel Cruz',   'initials' => 'DC', 'tone' => 'blue',  'program' => 'BSIT 4B', 'date' => 'Fri, Nov 06', 'in' => '8:34 AM', 'out' => '5:05 PM', 'hours' => '7.5', 'status' => 'Late'],
        ['id' => 'e3', 'name' => 'Sofia Lee',     'initials' => 'SL', 'tone' => 'amber', 'program' => 'BSCS 4A', 'date' => 'Fri, Nov 06', 'in' => '7:58 AM', 'out' => '5:00 PM', 'hours' => '8.0', 'status' => 'Present'],
        ['id' => 'e4', 'name' => 'Paolo Ramos',   'initials' => 'PR', 'tone' => 'red',   'program' => 'BSCS 4B', 'date' => 'Fri, Nov 06', 'in' => '8:00 AM', 'out' => '5:02 PM', 'hours' => '8.0', 'status' => 'Present'],
        ['id' => 'e5', 'name' => 'Maria Santos',  'initials' => 'MS', 'tone' => 'green', 'program' => 'BSIT 4A', 'date' => 'Thu, Nov 05', 'in' => '7:58 AM', 'out' => '5:01 PM', 'hours' => '8.0', 'status' => 'Present'],
        ['id' => 'e6', 'name' => 'Daniel Cruz',   'initials' => 'DC', 'tone' => 'blue',  'program' => 'BSIT 4B', 'date' => 'Thu, Nov 05', 'in' => '8:03 AM', 'out' => '5:00 PM', 'hours' => '8.0', 'status' => 'Present'],
        ['id' => 'e7', 'name' => 'Sofia Lee',     'initials' => 'SL', 'tone' => 'amber', 'program' => 'BSCS 4A', 'date' => 'Thu, Nov 05', 'in' => '8:21 AM', 'out' => '5:10 PM', 'hours' => '8.0', 'status' => 'Late'],
    ];

    $weekHours = [
        ['name' => 'Maria Santos', 'hours' => 32],
        ['name' => 'Daniel Cruz',  'hours' => 30],
        ['name' => 'Sofia Lee',    'hours' => 36],
        ['name' => 'Paolo Ramos',  'hours' => 24],
    ];
    $weekTarget = 40;

    $statusStyles = [
        'Present' => 'bg-emerald-50 text-emerald-700',
        'Late'    => 'bg-amber-50 text-amber-700',
    ];

    $filters = ['pending' => 'Pending', 'verified' => 'Verified', 'flagged' => 'Flagged', 'all' => 'All'];
@endphp

<x-layouts.app title="Verify attendance">

    <div x-data="attendanceVerifier">

        {{-- Header --}}
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Supervisor workspace</p>
                <h1 class="mt-2 text-4xl font-normal tracking-tight text-slate-900">Verify attendance</h1>
                <p class="mt-2 text-sm text-slate-500">Confirm the time records your interns submitted, or flag entries that look wrong.</p>
            </div>

            <button type="button" @click="verifySelected()" :disabled="selected.length === 0"
                    class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                <span x-text="selected.length ? 'Verify selected (' + selected.length + ')' : 'Verify selected'">Verify selected</span>
            </button>
        </div>

        {{-- Stat cards --}}
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <div class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div>
                    <p class="text-xs text-slate-500">Awaiting verification</p>
                    <p class="mt-2 text-3xl font-medium text-slate-900" x-text="count('pending')">0</p>
                    <p class="mt-1 text-[11px] text-slate-400">Requires your confirmation</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icons['clock'] }}" /></svg>
                </span>
            </div>

            <div class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div>
                    <p class="text-xs text-slate-500">Verified this week</p>
                    <p class="mt-2 text-3xl font-medium text-slate-900" x-text="12 + count('verified')">12</p>
                    <p class="mt-1 text-[11px] text-slate-400">Time records confirmed</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icons['check'] }}" /></svg>
                </span>
            </div>

            <div class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div>
                    <p class="text-xs text-slate-500">Flagged entries</p>
                    <p class="mt-2 text-3xl font-medium text-slate-900" x-text="count('flagged')">0</p>
                    <p class="mt-1 text-[11px] text-slate-400">Sent back to the intern</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icons['alert'] }}" /></svg>
                </span>
            </div>

            <div class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div>
                    <p class="text-xs text-slate-500">Assigned interns</p>
                    <p class="mt-2 text-3xl font-medium text-slate-900">4</p>
                    <p class="mt-1 text-[11px] text-slate-400">TechCore Solutions</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icons['students'] }}" /></svg>
                </span>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-[1fr_340px]">

            {{-- Entries --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-6">
                    <div>
                        <h2 class="text-lg text-slate-900">Time records</h2>
                        <p class="mt-1 text-xs text-slate-400">Submitted by your interns this week</p>
                    </div>

                    <div class="inline-flex gap-1 rounded-xl bg-slate-100 p-1">
                        @foreach ($filters as $key => $label)
                            <button type="button" @click="filter = '{{ $key }}'"
                                    :class="filter === '{{ $key }}' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                    class="rounded-lg px-3.5 py-1.5 text-xs font-semibold transition">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </header>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left">
                        <thead class="bg-slate-50/70">
                            <tr class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                <th class="w-12 px-6 py-3.5">
                                    <input type="checkbox" @change="toggleAll()" :checked="allPendingSelected()"
                                           class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-3 py-3.5">Intern</th>
                                <th class="px-3 py-3.5">Date</th>
                                <th class="px-3 py-3.5">Time in / out</th>
                                <th class="px-3 py-3.5">Hours</th>
                                <th class="px-3 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($entries as $e)
                                <tr x-show="filter === 'all' || state['{{ $e['id'] }}'] === filter" class="transition hover:bg-slate-50/70">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" value="{{ $e['id'] }}" x-model="selected"
                                               :disabled="state['{{ $e['id'] }}'] !== 'pending'"
                                               class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 disabled:opacity-30">
                                    </td>
                                    <td class="px-3 py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-[11px] font-bold {{ $avatarTones[$e['tone']] }}">{{ $e['initials'] }}</span>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $e['name'] }}</p>
                                                <p class="text-[11px] text-slate-400">{{ $e['program'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-slate-600">{{ $e['date'] }}</td>
                                    <td class="px-3 py-4 text-sm text-slate-600">{{ $e['in'] }} &ndash; {{ $e['out'] }}</td>
                                    <td class="px-3 py-4 text-sm text-slate-600">{{ $e['hours'] }}</td>
                                    <td class="px-3 py-4">
                                        <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $statusStyles[$e['status']] }}">{{ $e['status'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div x-show="state['{{ $e['id'] }}'] === 'pending'" class="inline-flex gap-2">
                                            <button type="button" @click="set('{{ $e['id'] }}', 'verified')"
                                                    class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-700">Verify</button>
                                            <button type="button" @click="set('{{ $e['id'] }}', 'flagged')"
                                                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">Flag</button>
                                        </div>
                                        <div x-show="state['{{ $e['id'] }}'] !== 'pending'" x-cloak class="inline-flex items-center gap-3">
                                            <span class="text-xs font-semibold"
                                                  :class="state['{{ $e['id'] }}'] === 'verified' ? 'text-emerald-600' : 'text-rose-600'"
                                                  x-text="state['{{ $e['id'] }}'] === 'verified' ? 'Verified' : 'Flagged'"></span>
                                            <button type="button" @click="set('{{ $e['id'] }}', 'pending')"
                                                    class="text-xs text-slate-400 underline hover:text-slate-600">Undo</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p x-show="visibleCount() === 0" x-cloak class="px-6 py-14 text-center text-sm text-slate-400">
                    Nothing here. Try another filter.
                </p>
            </section>

            {{-- Weekly hours --}}
            <aside class="self-start rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg text-slate-900">Hours this week</h2>
                <p class="mt-1 text-xs text-slate-400">Per intern &middot; target {{ $weekTarget }} h</p>

                <ul class="mt-5 space-y-5">
                    @foreach ($weekHours as $w)
                        <li>
                            <div class="mb-2 flex justify-between text-xs">
                                <span class="text-slate-500">{{ $w['name'] }}</span>
                                <span class="font-semibold text-slate-800">{{ $w['hours'] }} h</span>
                            </div>
                            <div class="h-1.5 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $w['hours'] >= 32 ? 'bg-blue-600' : 'bg-amber-500' }}"
                                     style="width: {{ min(100, round(($w['hours'] / $weekTarget) * 100)) }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </aside>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('attendanceVerifier', () => ({
                ids: @json(collect($entries)->pluck('id')),
                state: {},
                selected: [],
                filter: 'pending',
                init() {
                    this.ids.forEach(id => { this.state[id] = 'pending'; });
                },
                set(id, value) {
                    this.state[id] = value;
                    this.selected = this.selected.filter(x => x !== id);
                },
                count(value) {
                    return this.ids.filter(id => this.state[id] === value).length;
                },
                pendingIds() {
                    return this.ids.filter(id => this.state[id] === 'pending');
                },
                allPendingSelected() {
                    const p = this.pendingIds();
                    return p.length > 0 && p.every(id => this.selected.includes(id));
                },
                toggleAll() {
                    this.selected = this.allPendingSelected() ? [] : this.pendingIds();
                },
                verifySelected() {
                    this.selected.forEach(id => { this.state[id] = 'verified'; });
                    this.selected = [];
                },
                visibleCount() {
                    return this.ids.filter(id => this.filter === 'all' || this.state[id] === this.filter).length;
                },
            }));
        });
    </script>
</x-layouts.app>