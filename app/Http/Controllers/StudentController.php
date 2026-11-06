<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Requirement;
use App\Models\Setting;
use App\Support\RequirementSchedule;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Enums\RiskLevel;
use App\Models\Student;

class StudentController extends Controller
{
    // Whitelisted sort options (label shown in UI + SQL expression)
    private const SORTS = [
        'risk'     => ['label' => 'Risk level',     'sql' => "CASE risk_level WHEN 'high' THEN 0 WHEN 'medium' THEN 1 ELSE 2 END"],
        'name'     => ['label' => 'Name',           'sql' => 'last_name'],
        'progress' => ['label' => 'OJT progress',   'sql' => 'rendered_hours / NULLIF(required_hours, 0)'],
        'hours'    => ['label' => 'Hours rendered', 'sql' => 'rendered_hours'],
    ];

    public function index(Request $request)
    {
        $risk   = RiskLevel::tryFrom((string) $request->query('risk'));
        $search = trim((string) $request->query('search'));
        $sort   = array_key_exists($request->query('sort'), self::SORTS) ? $request->query('sort') : 'risk';
        $dir    = $request->query('dir') === 'desc' ? 'desc' : 'asc';

        $students = Student::query()
            ->with('company')
            ->search($search)
            ->when($risk, fn ($q) => $q->where('risk_level', $risk))
            ->orderByRaw(self::SORTS[$sort]['sql'] . ' ' . $dir)
            ->orderBy('last_name')
            ->paginate(10)
            ->withQueryString();

        // Counts for the summary strip + tabs, one query
        $counts = Student::query()
            ->selectRaw('risk_level, count(*) as total')
            ->groupBy('risk_level')
            ->pluck('total', 'risk_level');

        return view('students.index', [
            'students' => $students,
            'counts'   => $counts,
            'total'    => $counts->sum(),
            'risk'     => $risk,
            'search'   => $search,
            'sort'     => $sort,
            'dir'      => $dir,
            'sorts'    => collect(self::SORTS)->pluck('label'),
        ]);
    }
    public function create()
{
    return view('students.form', $this->formData(new Student([
        'required_hours' => (int) Setting::get('default_hours', 486),
    ])));
}

public function store(Request $request)
{
    $student = Student::create($this->validated($request));

    return redirect()->route('students.show', $student)->with('status', "{$student->full_name} was added.");
}

public function show(Student $student)
{
    $student->load(['company', 'program', 'placements.company']);

    $placement   = $student->placements->firstWhere('status', 'active');
    $submissions = $student->submissions()->get();

    // Requirement checklist grouped by phase; recurring items list each due period
    $checklist = Requirement::orderBy('sort_order')->get()
        ->groupBy('phase')
        ->map(fn ($group) => $group->map(function ($req) use ($submissions, $placement) {
            $mine = $submissions->where('requirement_id', $req->id)->keyBy('period');

            $items = $req->recurrence === 'none'
                ? [['period' => '', 'label' => null, 'submission' => $mine->get('')]]
                : collect(RequirementSchedule::periods($req, $placement?->start_date))
                    ->take(-8)   // the 8 most recent due periods
                    ->map(fn ($p) => [
                        'period'     => $p,
                        'label'      => RequirementSchedule::label($req, $p),
                        'submission' => $mine->get($p),
                    ])->values()->all();

            return ['requirement' => $req, 'items' => $items];
        }));

    return view('students.show', [
        'student'     => $student,
        'placement'   => $placement,
        'checklist'   => $checklist,
        'evaluations' => $student->evaluations()->orderByDesc('submitted_on')->get(),
    ]);
}

public function edit(Student $student)
{
    return view('students.form', $this->formData($student));
}

public function update(Request $request, Student $student)
{
    $student->update($this->validated($request, $student));

    return redirect()->route('students.show', $student)->with('status', 'Student updated.');
}

public function destroy(Student $student)
{
    $student->delete();

    return redirect()->route('students.index')->with('status', "{$student->full_name} was removed.");
}

private function formData(Student $student): array
{
    return [
        'student'  => $student,
        'programs' => Program::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name', 'required_hours']),
    ];
}

private function validated(Request $request, ?Student $student = null): array
{
    return $request->validate([
        'student_number' => ['required', 'string', 'max:30', Rule::unique('students', 'student_number')->ignore($student)],
        'first_name'     => ['required', 'string', 'max:60'],
        'last_name'      => ['required', 'string', 'max:60'],
        'program_id'     => ['nullable', 'exists:programs,id'],
        'required_hours' => ['required', 'integer', 'min:1', 'max:2000'],
    ]);
}
}
