@php
    $icons = [
        'bell'     => 'M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0',
        'document' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
        'clock'    => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'check'    => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'alert'    => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-4.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
    ];

    // type: requirements | attendance | system
    $notifications = [
        ['type' => 'requirements', 'icon' => 'alert',    'tone' => 'bg-amber-50 text-amber-600',     'unread' => true,  'title' => 'Revision needed: Group Insurance or PhilHealth', 'body' => 'The notary seal is not visible. Please upload a clearer copy.', 'time' => '12 minutes ago'],
        ['type' => 'requirements', 'icon' => 'clock',    'tone' => 'bg-rose-50 text-rose-600',       'unread' => true,  'title' => 'Weekly report due Wednesday',                    'body' => 'Your Weekly Accomplishment Report is due on Nov 11.',       'time' => '2 hours ago'],
        ['type' => 'attendance',   'icon' => 'check',    'tone' => 'bg-emerald-50 text-emerald-600', 'unread' => true,  'title' => 'Attendance verified',                            'body' => 'Ramon Diaz verified your time records for Nov 03 to Nov 05.', 'time' => 'Yesterday'],
        ['type' => 'system',       'icon' => 'bell',     'tone' => 'bg-blue-50 text-blue-600',       'unread' => false, 'title' => 'Mid-term evaluation submitted',                  'body' => 'Your supervisor submitted your mid-term evaluation. It is now under review.', 'time' => 'Nov 02'],
        ['type' => 'requirements', 'icon' => 'check',    'tone' => 'bg-emerald-50 text-emerald-600', 'unread' => false, 'title' => 'Requirement approved: Signed Internship Plan',   'body' => 'Your coordinator approved this document.',                  'time' => 'Oct 30'],
        ['type' => 'attendance',   'icon' => 'alert',    'tone' => 'bg-amber-50 text-amber-600',     'unread' => false, 'title' => 'Late arrival recorded',                         'body' => 'You clocked in at 8:21 AM on Nov 04. Submit a note if this needs correcting.', 'time' => 'Oct 28'],
        ['type' => 'system',       'icon' => 'document', 'tone' => 'bg-violet-50 text-violet-600',   'unread' => false, 'title' => 'Monitoring visit completed',                    'body' => 'Your coordinator logged a monitoring visit on Oct 21.',     'time' => 'Oct 21'],
        ['type' => 'system',       'icon' => 'bell',     'tone' => 'bg-blue-50 text-blue-600',       'unread' => false, 'title' => 'Welcome to IntelliOJT',                         'body' => 'Submit your requirements, record attendance, and track your progress here.', 'time' => 'Jan 05'],
    ];

    $filters = [
        'all'          => 'All',
        'unread'       => 'Unread',
        'requirements' => 'Requirements',
        'attendance'   => 'Attendance',
    ];

    $unreadCount = collect($notifications)->where('unread', true)->count();
    $countByType = collect($notifications)->countBy('type');

    $typeSummary = [
        ['label' => 'Requirements', 'count' => $countByType['requirements'] ?? 0, 'dot' => 'bg-amber-400'],
        ['label' => 'Attendance',   'count' => $countByType['attendance'] ?? 0,   'dot' => 'bg-emerald-500'],
        ['label' => 'System',       'count' => $countByType['system'] ?? 0,       'dot' => 'bg-blue-500'],
    ];
@endphp

<x-layouts.app title="Notifications">

    <div x-data="{ filter: 'all', allRead: false }">

        {{-- Header --}}
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Inbox</p>
                <h1 class="mt-2 text-4xl font-normal tracking-tight text-slate-900">Notifications</h1>
                <p class="mt-2 text-sm text-slate-500">Deadlines, review results and attendance updates from your coordinator and supervisor.</p>
            </div>

            <button type="button" @click="allRead = true" :disabled="allRead"
                    class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                Mark all as read
            </button>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-[1fr_320px]">

            {{-- Notification list --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-6">
                    <div>
                        <h2 class="text-lg text-slate-900">Recent activity</h2>
                        <p class="mt-1 text-xs text-slate-400">
                            <span x-text="allRead ? 0 : {{ $unreadCount }}">{{ $unreadCount }}</span> unread
                        </p>
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

                <ul class="divide-y divide-slate-100">
                    @foreach ($notifications as $n)
                        <li x-show="filter === 'all' || (filter === 'unread' ? {{ $n['unread'] ? '!allRead' : 'false' }} : filter === '{{ $n['type'] }}')"
                            class="flex items-start gap-4 px-6 py-5 transition hover:bg-slate-50/70">

                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $n['tone'] }}">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="{{ $icons[$n['icon']] }}" />
                                </svg>
                            </span>

                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-900">{{ $n['title'] }}</p>
                                <p class="mt-1 text-xs leading-relaxed text-slate-500">{{ $n['body'] }}</p>
                                <p class="mt-2 text-[11px] text-slate-400">{{ $n['time'] }}</p>
                            </div>

                            @if ($n['unread'])
                                <span x-show="!allRead" class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full bg-blue-600"></span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </section>

            {{-- Summary --}}
            <aside class="space-y-6 self-start">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg text-slate-900">Summary</h2>
                    <p class="mt-1 text-xs text-slate-400">By category</p>

                    <ul class="mt-5 space-y-3">
                        @foreach ($typeSummary as $t)
                            <li class="flex items-center justify-between text-xs">
                                <span class="flex items-center gap-2 text-slate-500">
                                    <span class="h-2 w-2 rounded-full {{ $t['dot'] }}"></span>{{ $t['label'] }}
                                </span>
                                <span class="font-semibold text-slate-800">{{ $t['count'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg text-slate-900">Delivery</h2>
                    <p class="mt-1 text-xs text-slate-400">How you receive updates</p>

                    <ul class="mt-5 space-y-4">
                        @foreach (['In-app notifications', 'Email reminders', 'Deadline alerts (48 hours before)'] as $i => $label)
                            <li x-data="{ on: {{ $i === 1 ? 'false' : 'true' }} }" class="flex items-center justify-between gap-3">
                                <span class="text-xs text-slate-600">{{ $label }}</span>
                                <button type="button" role="switch" :aria-checked="on" @click="on = !on"
                                        :class="on ? 'bg-blue-600' : 'bg-slate-200'"
                                        class="relative h-6 w-11 shrink-0 rounded-full transition">
                                    <span :class="on ? 'translate-x-5' : 'translate-x-0.5'"
                                          class="absolute top-0.5 h-5 w-5 rounded-full bg-white shadow transition"></span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </aside>
        </div>
    </div>
</x-layouts.app>