@php
    $statusStyle = [
        'approved'  => 'bg-emerald-50 text-emerald-700',
        'submitted' => 'bg-blue-50 text-blue-700',
        'rejected'  => 'bg-rose-50 text-rose-700',
        'missing'   => 'bg-slate-100 text-slate-500',
    ];

    $approvedWord = ['document' => 'Approved', 'participation' => 'Attended', 'received' => 'Received'];

    $statusLabel = fn ($status, $type) => match ($status) {
        'approved'  => $approvedWord[$type] ?? 'Approved',
        'submitted' => 'Awaiting review',
        'rejected'  => 'Rejected',
        default     => 'Not submitted',
    };

    $progress = $student->progress;
@endphp

<x-layouts.app :title="$student->full_name">

    <a href="{{ route('students.index') }}" class="text-xs font-semibold text-slate-400 hover:text-blue-700">&larr; Students</a>

    {{-- Header --}}
    <div class="mt-4 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-center gap-5">
            <span class="flex h-16 w-16 items-center justify-center rounded-2xl text-lg font-semibold {{ $student->risk_level->avatar() }}">{{ $student->initials }}</span>
            <div>
                <h1 class="text-3xl font-normal tracking-tight text-slate-900">{{ $student->full_name }}</h1>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $student->student_number }}
                    @if ($student->program) · {{ $student->program->code }} @endif
                </p>
                <span class="mt-2 inline-block rounded-full px-3 py-1 text-[11px] font-semibold {{ $student->risk_level->badge() }}">{{ $student->risk_level->label() }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('students.edit', $student) }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm hover:bg-slate-50">Edit</a>
            <form method="POST" action="{{ route('students.destroy', $student) }}"
                  onsubmit="return confirm('Remove {{ addslashes($student->full_name) }}? Their requirements, placements and evaluations will be deleted too.')">
                @csrf @method('DELETE')
                <button class="rounded-xl px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
            </form>
        </div>
    </div>

    @if (session('status'))
        <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif

    <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-[2fr_1fr]">

        {{-- Left: progress + requirement checklist --}}
        <div class="space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-end justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">OJT progress</h2>
                        <p class="mt-1 text-xs text-slate-400">Hours rendered against the program requirement</p>
                    </div>
                    <p class="text-2xl font-semibold text-slate-900">{{ $progress }}%</p>
                </div>
                <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-200">
                    <div class="h-full rounded-full bg-blue-600" style="width: {{ $progress }}%"></div>
                </div>
                <p class="mt-2 text-xs text-slate-500">{{ $student->rendered_hours }} of {{ $student->required_hours }} hours</p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-100 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Requirements</h2>
                </header>

                @foreach (\App\Models\Requirement::PHASES as $phaseKey => $phaseLabel)
                    <div class="border-b border-slate-100 last:border-b-0">
                        <p class="bg-slate-50 px-6 py-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ $phaseLabel }}</p>

                        <ul class="divide-y divide-slate-100">
                            @foreach ($checklist[$phaseKey] ?? [] as $entry)
                                @php $req = $entry['requirement']; @endphp
                                <li class="px-6 py-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <p class="text-sm font-medium text-slate-800">{{ $req->name }}</p>

                                        @if ($req->recurrence === 'none')
                                            @php
                                                $sub    = $entry['items'][0]['submission'];
                                                $status = $sub?->status ?? 'missing';
                                            @endphp
                                            <span class="shrink-0 rounded-full px-3 py-1 text-[11px] font-semibold {{ $statusStyle[$status] }}">{{ $statusLabel($status, $req->type) }}</span>
                                        @endif
                                    </div>

                                    @if ($req->recurrence !== 'none')
                                        @if (empty($entry['items']))
                                            <p class="mt-2 text-xs text-slate-400">Nothing due yet. This starts after the placement begins.</p>
                                        @else
                                            <div class="mt-2 flex flex-wrap gap-1.5">
                                                @foreach ($entry['items'] as $item)
                                                    @php $status = $item['submission']?->status ?? 'missing'; @endphp
                                                    <span class="rounded-md px-2 py-1 text-[10px] font-semibold {{ $statusStyle[$status] }}"
                                                          title="{{ $statusLabel($status, $req->type) }}">{{ $item['label'] }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </section>
        </div>

        {{-- Right: placement + evaluations --}}
        <div class="space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900">Placement</h2>
                    <a href="{{ Route::has('placements.index') ? route('placements.index') : '#' }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Manage</a>
                </div>

                @if ($placement)
                    <p class="mt-4 text-sm font-semibold text-slate-900">{{ $placement->company->name }}</p>
                    <p class="text-xs text-slate-400">{{ $placement->company->industry }}</p>
                    <dl class="mt-4 space-y-2 text-xs">
                        <div class="flex justify-between"><dt class="text-slate-400">Supervisor</dt><dd class="font-medium text-slate-700">{{ $placement->company->supervisor_name ?? '—' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-400">Started</dt><dd class="font-medium text-slate-700">{{ $placement->start_date->format('M j, Y') }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-400">Ends</dt><dd class="font-medium text-slate-700">{{ $placement->end_date?->format('M j, Y') ?? 'Not set' }}</dd></div>
                    </dl>
                @else
                    <p class="mt-4 text-sm text-slate-500">Not placed yet.</p>
                @endif

                @if ($student->placements->where('status', '!=', 'active')->isNotEmpty())
                    <p class="mt-5 text-[11px] font-semibold uppercase tracking-wider text-slate-400">History</p>
                    <ul class="mt-2 space-y-2 text-xs text-slate-500">
                        @foreach ($student->placements->where('status', '!=', 'active')->sortByDesc('start_date') as $old)
                            <li class="flex justify-between gap-3">
                                <span>{{ $old->company->name }}</span>
                                <span class="shrink-0 capitalize text-slate-400">{{ $old->status }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Evaluations</h2>

                <ul class="mt-4 divide-y divide-slate-100">
                    @forelse ($evaluations as $evaluation)
                        <li class="flex items-center justify-between py-3 text-sm">
                            <div>
                                <p class="font-medium capitalize text-slate-800">{{ $evaluation->term }}</p>
                                <p class="text-[11px] text-slate-400">{{ $evaluation->evaluator_name ?? '—' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-blue-600">{{ $evaluation->score ?? '—' }}</p>
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $evaluation->status->badge() }}">{{ $evaluation->status->label() }}</span>
                            </div>
                        </li>
                    @empty
                        <li class="py-6 text-center text-sm text-slate-400">No evaluations yet.</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </div>
</x-layouts.app>