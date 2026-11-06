<?php

namespace Database\Seeders;

use App\Models\Placement;
use App\Models\Requirement;
use App\Models\RequirementSubmission;
use App\Models\Student;
use App\Support\RequirementSchedule;
use Illuminate\Database\Seeder;

class CoordinatorSeeder extends Seeder
{
    public function run(): void
    {
        RequirementSubmission::query()->delete();
        Requirement::query()->delete();
        Placement::query()->delete();

        $now      = now();
        $students = Student::all();

        // ---- One active placement per student that already has a company ----
        $placements = $students->whereNotNull('company_id')->map(fn ($s) => [
            'student_id' => $s->id,
            'company_id' => $s->company_id,
            'start_date' => today()->subDays(random_int(20, 70))->toDateString(),
            'status'     => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ])->values()->all();

        foreach (array_chunk($placements, 500) as $chunk) {
            Placement::insert($chunk);
        }

        // ---- The coordinator's requirement list ----
        $definitions = [
            // phase, type, recurrence, name, description
            ['before', 'document', 'none', "Parent's / Guardian's Consent (Notarized)", null],
            ['before', 'document', 'none', 'Internship Agreement (between student and HTE; Notarized)', null],
            ['before', 'document', 'none', 'Signed Internship Plan', null],
            ['before', 'document', 'none', 'Certification of Fitness to Work ("medical")', null],
            ['before', 'document', 'none', 'COVID-19 Vaccination Card', 'Must show at least 2 doses (only 1 if J&J).'],
            ['before', 'document', 'none', 'Group Insurance, PhilHealth ID or MDR',
                'Group insurance (ML / GCash / Palawan, etc.) OR PhilHealth ID or MDR if the OJT location is outside your home city/municipality.'],
            ['before', 'participation', 'none', 'Pre-departure Orientation Seminar (PDOS)', 'Participation in the PDOS, confirmed by the coordinator.'],
            ['during', 'document', 'monthly', 'DTR (Daily Time Record)',
                'Signed DTR for the month. Submit on or before the first Wednesday of the following month (a January DTR is due on the first Wednesday of February).'],
            ['during', 'document', 'weekly', 'Weekly report',
                'Weekly report in the official format. Submit every Wednesday of the following week.'],
            ['after', 'received', 'none', "HTE Evaluation of Student's Performance",
                'Emailed or sent by courier by the HTE directly to the Practicum Instructor / Practicum Coordinator / SICT Dean. Record it here once received.'],
            ['after', 'document', 'none', 'Notarized Certificate of Completion (CoC)', 'Facilitated by the HTE.'],
            ['after', 'document', 'none', 'Final (Narrative) Report', null],
        ];

        $requirements = collect($definitions)->map(fn ($d, $i) => Requirement::create([
            'phase'       => $d[0],
            'type'        => $d[1],
            'recurrence'  => $d[2],
            'name'        => $d[3],
            'description' => $d[4],
            'is_required' => true,
            'sort_order'  => $i,
        ]));

        // ---- Submissions (students are still on duty, so nothing for "after OJT") ----
        $starts = Placement::pluck('start_date', 'student_id');
        $rows   = [];

        foreach ($students as $student) {
            $started = $starts[$student->id] ?? null;

            foreach ($requirements as $requirement) {
                if ($requirement->phase === 'after') {
                    continue;
                }

                $periods = $requirement->recurrence === 'none'
                    ? ['']
                    : RequirementSchedule::periods($requirement, $started);

                foreach ($periods as $period) {
                    $roll = random_int(1, 100);

                    if ($roll > 88) {
                        continue;   // not submitted
                    }

                    $status    = $roll <= 70 ? 'approved' : ($roll <= 80 ? 'submitted' : 'rejected');
                    $submitted = $now->copy()->subDays(random_int(1, 40));

                    $rows[] = [
                        'requirement_id' => $requirement->id,
                        'student_id'     => $student->id,
                        'period'         => $period,
                        'status'         => $status,
                        'submitted_at'   => $submitted,
                        'reviewed_at'    => $status === 'submitted' ? null : $submitted->copy()->addDay(),
                        'remarks'        => $status === 'rejected' ? 'Please upload a clearer copy.' : null,
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ];
                }
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            RequirementSubmission::insert($chunk);
        }
    }
}