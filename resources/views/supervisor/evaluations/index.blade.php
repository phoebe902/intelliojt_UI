@php
    $icons = [
        'clock'    => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'check'    => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'star'     => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z',
        'calendar' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
    ];

    $avatarTones = [
        'green' => 'bg-emerald-50 text-emerald-700',
        'amber' => 'bg-amber-50 text-amber-700',
        'blue'  => 'bg-blue-50 text-blue-700',
        'red'   => 'bg-rose-50 text-rose-700',
    ];

    $criteria = [
        ['key' => 'technical',     'label' => 'Technical skills',    'hint' => 'Quality and accuracy of assigned work'],
        ['key' => 'attitude',      'label' => 'Work attitude',       'hint' => 'Professionalism and responsibility'],
        ['key' => 'communication', 'label' => 'Communication',       'hint' => 'Clarity with the team and clients'],
        ['key' => 'punctuality',   'label' => 'Punctuality',         'hint' => 'Attendance and timeliness'],
        ['key' => 'initiative',    'label' => 'Initiative',          'hint' => 'Willingness to learn and take ownership'],
        ['key' => 'teamwork',      'label' => 'Teamwork',            'hint' => 'Collaboration with colleagues'],
    ];

    $interns = [
        ['id' => 1, 'name' => 'Maria Santos', 'initials' => 'MS', 'tone' => 'green', 'program' => 'BSIT 4A', 'hours' => 364, 'status' => 'submitted',
         'ratings' => ['technical' => 5, 'attitude' => 5, 'communication' => 4, 'punctuality' => 4, 'initiative' => 5, 'teamwork' => 4],
         'comment' => 'Consistently delivers clean work and picks up new tools quickly.', 'recommend' => 'yes'],
        ['id' => 2, 'name' => 'Daniel Cruz',  'initials' => 'DC', 'tone' => 'blue',  'program' => 'BSIT 4B', 'hours' => 331, 'status' => 'draft',
         'ratings' => ['technical' => 4, 'attitude' => 4], 'comment' => '', 'recommend' => ''],
        ['id' => 3, 'name' => 'Sofia Lee',    'initials' => 'SL', 'tone' => 'amber', 'program' => 'BSCS 4A', 'hours' => 402, 'status' => 'pending', 'ratings' => [], 'comment' => '', 'recommend' => ''],
        ['id' => 4, 'name' => 'Paolo Ramos',  'initials' => 'PR', 'tone' => 'red',   'program' => 'BSCS 4B', 'hours' => 276, 'status' => 'pending', 'ratings' => [], 'comment' => '', 'recommend' => ''],
    ];

    $recommendOptions = [
        'yes'         => 'Recommend completion',
        'reservation' => 'With reservations',
        'no'          => 'Not yet ready',
    ];
@endphp

<x-layouts.app title="Evaluate interns">

    <div x-data="internEvaluator">

        {{-- Header --}}
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Supervisor workspace</p>
                <h1 class="mt-2 text-4xl font-normal tracking-tight text-slate-900">Evaluate interns</h1>
                <p class="mt-2 text-sm text-slate-500">Rate each intern against the program criteria. Your feedback goes to the OJT coordinator.</p>
            </div>

            <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white p-1.5 shadow-sm">
                @foreach (['Mid-term', 'Final'] as $p)
                    <button type="button" @click="period = '{{ $p }}'"
                            :class="period === '{{ $p }}' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-500 hover:bg-slate-50'"
                            class="rounded-xl px-4 py-2 text-sm font-semibold transition">{{ $p }}</button>
                @endforeach
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <div class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div>
                    <p class="text-xs text-slate-500">Awaiting evaluation</p>
                    <p class="mt-2 text-3xl font-medium text-slate-900" x-text="remaining()">0</p>
                    <p class="mt-1 text-[11px] text-slate-400">Interns not yet submitted</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icons['clock'] }}" /></svg>
                </span>
            </div>

            <div class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div>
                    <p class="text-xs text-slate-500">Submitted</p>
                    <p class="mt-2 text-3xl font-medium text-slate-900" x-text="interns.length - remaining()">0</p>
                    <p class="mt-1 text-[11px] text-slate-400">Sent to the coordinator</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icons['check'] }}" /></svg>
                </span>
            </div>

            <div class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div>
                    <p class="text-xs text-slate-500">Average score given</p>
                    <p class="mt-2 text-3xl font-medium text-slate-900">4.5</p>
                    <p class="mt-1 text-[11px] text-slate-400">Out of 5.0</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icons['star'] }}" /></svg>
                </span>
            </div>

            <div class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div>
                    <p class="text-xs text-slate-500">Next deadline</p>
                    <p class="mt-2 text-3xl font-medium text-slate-900">Nov 20</p>
                    <p class="mt-1 text-[11px] text-slate-400">Mid-term evaluations</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icons['calendar'] }}" /></svg>
                </span>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-[340px_1fr]">

            {{-- Intern list --}}
            <section class="self-start overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-100 p-6">
                    <h2 class="text-lg text-slate-900">Your interns</h2>
                    <p class="mt-1 text-xs text-slate-400">Select an intern to evaluate</p>
                </header>

                <ul class="divide-y divide-slate-100">
                    @foreach ($interns as $i)
                        <li>
                            <button type="button" @click="select({{ $i['id'] }})"
                                    :class="current === {{ $i['id'] }} ? 'bg-blue-50/60' : 'hover:bg-slate-50/70'"
                                    class="flex w-full items-center gap-3 px-6 py-4 text-left transition">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $avatarTones[$i['tone']] }}">{{ $i['initials'] }}</span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-slate-900">{{ $i['name'] }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $i['program'] }} &middot; {{ $i['hours'] }} h rendered</p>
                                </div>
                                <span class="whitespace-nowrap rounded-full px-2.5 py-1 text-[10px] font-semibold"
                                      :class="badgeClass({{ $i['id'] }})"
                                      x-text="badgeLabel({{ $i['id'] }})"></span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </section>

            {{-- Evaluation form --}}
            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 p-6">
                    <div class="flex items-center gap-4">
                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-sm font-bold text-blue-700" x-text="intern.initials"></span>
                        <div>
                            <h2 class="text-lg text-slate-900" x-text="intern.name"></h2>
                            <p class="text-xs text-slate-400">
                                <span x-text="intern.program"></span> &middot; TechCore Solutions &middot;
                                <span x-text="period"></span> evaluation
                            </p>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-[11px] text-slate-400">Average score</p>
                        <p class="text-3xl font-semibold text-blue-600" x-text="average"></p>
                    </div>
                </header>

                {{-- Submitted banner --}}
                <div x-show="form.status === 'submitted'" x-cloak
                     class="mx-6 mt-6 flex items-center justify-between gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    <span>Evaluation submitted to the coordinator.</span>
                    <button type="button" @click="form.status = 'draft'" class="text-xs font-semibold underline">Edit</button>
                </div>

                <div class="space-y-6 p-6">
                    @foreach ($criteria as $c)
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $c['label'] }}</p>
                                <p class="text-xs text-slate-400">{{ $c['hint'] }}</p>
                            </div>

                            <div class="flex gap-1.5">
                                @foreach (range(1, 5) as $n)
                                    <button type="button"
                                            @click="rate('{{ $c['key'] }}', {{ $n }})"
                                            :disabled="form.status === 'submitted'"
                                            :class="form.ratings['{{ $c['key'] }}'] === {{ $n }}
                                                ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25'
                                                : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                                            class="h-10 w-10 rounded-xl text-sm font-semibold transition disabled:cursor-not-allowed">
                                        {{ $n }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <p class="text-[11px] text-slate-400">1 = Needs improvement &middot; 3 = Meets expectations &middot; 5 = Outstanding</p>

                    {{-- Comments --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-900">Supervisor comments</label>
                        <textarea x-model="form.comment" :disabled="form.status === 'submitted'" rows="4"
                                  placeholder="Strengths, areas to improve, and any notes for the coordinator..."
                                  class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 placeholder:text-slate-300 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 disabled:bg-slate-50"></textarea>
                    </div>

                    {{-- Recommendation --}}
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Overall recommendation</p>
                        <div class="mt-2 grid gap-3 sm:grid-cols-3">
                            @foreach ($recommendOptions as $value => $label)
                                <button type="button" @click="form.status !== 'submitted' && (form.recommend = '{{ $value }}')"
                                        :class="form.recommend === '{{ $value }}' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-500 hover:bg-slate-50'"
                                        class="rounded-xl border px-4 py-3 text-xs font-semibold transition">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <footer class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 p-6">
                    <p class="text-xs" :class="toast ? 'text-emerald-600' : 'text-slate-400'"
                       x-text="toast || (complete ? 'Ready to submit.' : 'Rate every criterion and choose a recommendation to submit.')"></p>

                    <div class="flex gap-3">
                        <button type="button" @click="saveDraft()" :disabled="form.status === 'submitted'"
                                class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:opacity-40">
                            Save draft
                        </button>
                        <button type="button" @click="submit()" :disabled="!complete || form.status === 'submitted'"
                                class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-40">
                            Submit evaluation
                        </button>
                    </div>
                </footer>
            </section>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('internEvaluator', () => ({
                interns: @json($interns),
                criteria: @json($criteria),
                period: 'Mid-term',
                current: 1,
                forms: {},
                toast: '',
                init() {
                    this.interns.forEach(i => {
                        this.forms[i.id] = {
                            status: i.status,
                            ratings: Object.assign({}, i.ratings),
                            comment: i.comment,
                            recommend: i.recommend,
                        };
                    });
                    this.current = this.interns[0].id;
                },
                get form() { return this.forms[this.current]; },
                get intern() { return this.interns.find(i => i.id === this.current); },
                get average() {
                    const v = Object.values(this.form.ratings);
                    return v.length ? (v.reduce((a, b) => a + b, 0) / v.length).toFixed(1) : '—';
                },
                get complete() {
                    return this.criteria.every(c => this.form.ratings[c.key]) && this.form.recommend !== '';
                },
                select(id) {
                    this.current = id;
                    this.toast = '';
                },
                rate(key, n) {
                    if (this.form.status === 'submitted') return;
                    this.form.ratings[key] = n;
                    if (this.form.status === 'pending') this.form.status = 'draft';
                },
                remaining() {
                    return Object.values(this.forms).filter(f => f.status !== 'submitted').length;
                },
                saveDraft() {
                    this.form.status = 'draft';
                    this.toast = 'Draft saved.';
                },
                submit() {
                    this.form.status = 'submitted';
                    this.toast = 'Evaluation submitted.';
                },
                badgeLabel(id) {
                    const s = this.forms[id] ? this.forms[id].status : 'pending';
                    return { submitted: 'Submitted', draft: 'Draft', pending: 'Not started' }[s];
                },
                badgeClass(id) {
                    const s = this.forms[id] ? this.forms[id].status : 'pending';
                    return {
                        submitted: 'bg-emerald-50 text-emerald-700',
                        draft: 'bg-amber-50 text-amber-700',
                        pending: 'bg-slate-100 text-slate-500',
                    }[s];
                },
            }));
        });
    </script>
</x-layouts.app>