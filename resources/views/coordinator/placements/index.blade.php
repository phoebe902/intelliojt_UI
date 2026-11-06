@php
    $icons = [
        'building' => 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21',
        'clock'    => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'document' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
        'chart'    => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
        'chevron'  => 'M8.25 4.5l7.5 7.5-7.5 7.5',
    ];

    $statCards = [
        ['icon' => 'building', 'tone' => 'bg-blue-50 text-blue-600',       'value' => '124', 'label' => 'Active placements',  'note' => 'Across 32 partner HTEs'],
        ['icon' => 'clock',    'tone' => 'bg-amber-50 text-amber-600',     'value' => '8',   'label' => 'For approval',       'note' => 'Placement requests'],
        ['icon' => 'document', 'tone' => 'bg-rose-50 text-rose-600',       'value' => '12',  'label' => 'Unassigned students','note' => 'Need a host company'],
        ['icon' => 'chart',    'tone' => 'bg-emerald-50 text-emerald-600', 'value' => '91%', 'label' => 'Placement rate',      'note' => '+6% from last semester'],
    ];

    $statusStyles = [
        'Active'  => 'bg-emerald-50 text-emerald-700',
        'Review'  => 'bg-amber-50 text-amber-700',
        'Pending' => 'bg-indigo-50 text-indigo-700',
    ];

    $placementRows = [
        ['name' => 'Maria Santos',   'section' => 'BSIT 4A', 'company' => 'TechCore Solutions', 'supervisor' => 'Ramon Diaz',      'start' => 'Jan 15, 2024', 'status' => 'Active'],
        ['name' => 'Joshua Lim',     'section' => 'BSIT 4B', 'company' => 'Cloudline Labs',     'supervisor' => 'Albert Tan',      'start' => 'Jan 22, 2024', 'status' => 'Active'],
        ['name' => 'Angela Reyes',   'section' => 'BSCS 4A', 'company' => 'Northpeak Digital',  'supervisor' => 'Jenna Cruz',      'start' => 'Jan 08, 2024', 'status' => 'Active'],
        ['name' => 'Carlo Mendoza',  'section' => 'BSIT 4A', 'company' => 'Vertex Systems',     'supervisor' => 'Vera Santos',     'start' => 'Feb 05, 2024', 'status' => 'Review'],
        ['name' => 'Patricia Gomez', 'section' => 'BSCS 4B', 'company' => 'Brightlane Tech',    'supervisor' => 'Mark Villanueva', 'start' => 'Feb 12, 2024', 'status' => 'Pending'],
    ];
@endphp

<x-layouts.app title="Placements">

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Coordinator workspace</p>
            <h1 class="mt-2 text-4xl font-normal tracking-tight text-slate-900">Student placements</h1>
            <p class="mt-2 text-sm text-slate-500">Assign students to host training establishments and manage placement details.</p>
        </div>

        <button type="button"
                class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700">
            + New placement
        </button>
    </div>

    {{-- Stat cards --}}
    <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($statCards as $card)
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

    {{-- Current placements --}}
    <section class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <header class="flex items-center justify-between p-6">
            <div>
                <h2 class="text-lg text-slate-900">Current placements</h2>
                <p class="mt-1 text-xs text-slate-400">{{ count($placementRows) }} students shown</p>
            </div>
            <button type="button"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                Filter cohort
            </button>
        </header>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left">
                <thead class="border-y border-slate-100 bg-slate-50/70">
                    <tr class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-3.5">Student</th>
                        <th class="px-6 py-3.5">Host company</th>
                        <th class="px-6 py-3.5">Supervisor</th>
                        <th class="px-6 py-3.5">Start date</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($placementRows as $row)
                        <tr class="cursor-pointer transition hover:bg-slate-50/70">
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-slate-900">{{ $row['name'] }}</p>
                                <p class="text-[11px] text-slate-400">{{ $row['section'] }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $row['company'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $row['supervisor'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $row['start'] }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $statusStyles[$row['status']] }}">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-slate-400">
                                <svg class="inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="{{ $icons['chevron'] }}" />
                                </svg>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>