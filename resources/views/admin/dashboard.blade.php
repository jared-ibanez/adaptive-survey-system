<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Adaptive Competency Survey</title>
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen bg-slate-950 text-white">
    <main class="mx-auto max-w-7xl px-6 py-10">

        <!-- Header -->
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-[11px] uppercase tracking-wider text-white/50">Admin</p>
                <h1 class="text-2xl font-bold">Survey Analytics</h1>
                <p class="mt-1 text-sm text-white/70">Filter and interpret results quickly.</p>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-white/80 hover:bg-white/10 transition">
                    Logout
                </button>
            </form>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.dashboard') }}"
              class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm text-white/80">School</label>
                    <select name="school"
                            class="w-full rounded-xl border border-white/10 bg-slate-900/40 px-4 py-3 text-white outline-none focus:border-white/25 focus:ring-2 focus:ring-white/10">
                        <option value="" class="bg-slate-900">All schools</option>
                        @foreach($schools as $s)
                            <option value="{{ $s }}" class="bg-slate-900" {{ $filters['school']===$s?'selected':'' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm text-white/80">Strand</label>
                    <select name="strand"
                            class="w-full rounded-xl border border-white/10 bg-slate-900/40 px-4 py-3 text-white outline-none focus:border-white/25 focus:ring-2 focus:ring-white/10">
                        <option value="" class="bg-slate-900">All strands</option>
                        @foreach($strands as $st)
                            <option value="{{ $st }}" class="bg-slate-900" {{ $filters['strand']===$st?'selected':'' }}>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm text-white/80">Grade</label>
                    <select name="gender"
                            class="w-full rounded-xl border border-white/10 bg-slate-900/40 px-4 py-3 text-white outline-none focus:border-white/25 focus:ring-2 focus:ring-white/10">
                        <option value="" class="bg-slate-900">All gender</option>
                        @foreach($gender as $g)
                            <option value="{{ $g }}" class="bg-slate-900" {{ $filters['gender']===$g?'selected':'' }}>
                                {{ $g }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-6 py-3 text-sm text-white/80 hover:bg-white/10 transition">
                    Reset
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 font-semibold text-slate-900 shadow-lg shadow-white/10 transition hover:-translate-y-0.5 hover:bg-white/95 focus:outline-none focus:ring-2 focus:ring-white/30">
                    Apply Filters
                </button>
            </div>
        </form>

        <!-- KPI Cards -->
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                <p class="text-xs uppercase tracking-wider text-white/50">Total Submissions</p>
                <p class="mt-2 text-3xl font-extrabold">{{ $totalSubmissions }}</p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                <p class="text-xs uppercase tracking-wider text-white/50">Average Total Score</p>
                <p class="mt-2 text-3xl font-extrabold">{{ $avgTotalScore }}</p>
                <p class="mt-1 text-xs text-white/55">out of 90</p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                <p class="text-xs uppercase tracking-wider text-white/50">Analytics</p>
                <p class="mt-2 text-sm text-white/70">Use charts + heatmap for faster interpretation.</p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                <p class="text-xs uppercase tracking-wider text-white/50">Actions</p>
                <a href="{{ route('admin.submissions') }}"
                   class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-white px-5 py-3 font-semibold text-slate-900 shadow-lg shadow-white/10 transition hover:-translate-y-0.5 hover:bg-white/95">
                    View Submissions
                </a>
            </div>
        </div>

        <!-- Charts -->
        <div class="mt-10 grid gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-5 lg:col-span-1">
                <p class="text-sm font-semibold text-white/85">Overall Competency Distribution</p>
                <div class="mt-4">
                    <canvas id="overallDonut"></canvas>
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-5 lg:col-span-2">
                <p class="text-sm font-semibold text-white/85">Average Scores per Dimension</p>
                <p class="mt-1 text-xs text-white/55">Averages out of 15 (based on filtered data)</p>
                <div class="mt-4">
                    <canvas id="dimensionBar"></canvas>
                </div>
            </div>
        </div>

        <div class="mt-4 rounded-2xl border border-white/10 bg-white/5 p-5">
            <p class="text-sm font-semibold text-white/85">Top Schools by Submissions</p>
            <p class="mt-1 text-xs text-white/55">Top 12 schools (based on current filters)</p>
            <div class="mt-4">
                <canvas id="schoolBar"></canvas>
            </div>
        </div>

        <!-- Heatmap -->
        @php
            // Color classes based on band thresholds
            // >= 12 = high, 8-11.99 = moderate, <8 = low
            $heatClass = function($val) {
                if ($val >= 12) return 'bg-emerald-500/15 border-emerald-500/25';
                if ($val >= 8) return 'bg-amber-500/15 border-amber-500/25';
                return 'bg-rose-500/15 border-rose-500/25';
            };
        @endphp

        <section class="mt-10">
            <h2 class="text-lg font-bold">Dimension Heatmap</h2>
            <p class="mt-1 text-sm text-white/60">
                Color indicates the average band: High (green), Moderate (amber), Low (red).
            </p>

            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($dimensionAverages as $label => $avg)
                    <div class="rounded-2xl border {{ $heatClass($avg) }} p-5">
                        <p class="text-sm font-semibold text-white/85">{{ $label }}</p>
                        <p class="mt-2 text-3xl font-extrabold text-white/90">{{ $avg }}</p>
                        <p class="mt-1 text-xs text-white/60">out of 15</p>
                    </div>
                @endforeach
            </div>
        </section>
                <!-- ✅ NEW: Reliability Section -->
        <section class="mt-10">
            <h2 class="text-lg font-bold">Instrument Reliability (Internal Use)</h2>
            <p class="mt-1 text-sm text-white/60">
                Computed from <span class="font-semibold text-white/80">filtered completed submissions</span>.
                Submissions with any <span class="font-semibold text-white/80">custom/null</span> scores in a dimension are excluded for that dimension.
            </p>
            
            <div class="mt-4 rounded-2xl border border-white/10 bg-white/5 p-5">
            <p class="text-xs uppercase tracking-wider text-white/50">Cronbach’s α Interpretation (Guide)</p>

            <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-4 text-sm">
                <div class="rounded-xl border border-white/10 bg-slate-900/30 px-4 py-3">
                    <p class="font-semibold text-white/85">≥ 0.90</p>
                    <p class="mt-1 text-xs text-white/60">Excellent (very high)</p>
                </div>

                <div class="rounded-xl border border-white/10 bg-slate-900/30 px-4 py-3">
                    <p class="font-semibold text-white/85">0.80 – 0.89</p>
                    <p class="mt-1 text-xs text-white/60">Good</p>
                </div>

                <div class="rounded-xl border border-white/10 bg-slate-900/30 px-4 py-3">
                    <p class="font-semibold text-white/85">0.70 – 0.79</p>
                    <p class="mt-1 text-xs text-white/60">Acceptable</p>
                </div>

                <div class="rounded-xl border border-white/10 bg-slate-900/30 px-4 py-3">
                    <p class="font-semibold text-white/85">&lt; 0.70</p>
                    <p class="mt-1 text-xs text-white/60">Needs review</p>
                </div>
            </div>

            <p class="mt-3 text-xs text-white/55 leading-relaxed">
                Note: α is influenced by sample size and number of items. Interpret alongside item–total correlations and “α if deleted.”
            </p>
        </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <p class="text-xs uppercase tracking-wider text-white/50">Overall (30 items)</p>
                    <p class="mt-2 text-3xl font-extrabold">
                        {{ $overallReliability['alpha'] ?? '—' }}
                    </p>
                    <p class="mt-1 text-xs text-white/60">
                        Cronbach’s α • N={{ $overallReliability['n'] ?? 0 }}
                    </p>
                </div>

                @foreach($reliability as $label => $r)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-wider text-white/50">{{ $label }}</p>
                        <p class="mt-2 text-3xl font-extrabold">
                            {{ $r['alpha'] ?? '—' }}
                        </p>
                        <p class="mt-1 text-xs text-white/60">
                            Cronbach’s α • N={{ $r['n'] ?? 0 }} • k={{ $r['k'] ?? 0 }}
                        </p>
                    </div>
                @endforeach
            </div>

            <!-- Level 2 Diagnostics -->
            <div class="mt-6 space-y-4">
                @foreach($reliability as $label => $r)
                    <details class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <summary class="cursor-pointer list-none select-none">
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-white/85">{{ $label }} — Item Diagnostics</p>
                                    <p class="text-xs text-white/55">
                                        α={{ $r['alpha'] ?? '—' }} • N={{ $r['n'] ?? 0 }} • k={{ $r['k'] ?? 0 }}
                                    </p>
                                </div>
                                <span class="text-xs text-white/60">Click to expand</span>
                            </div>
                        </summary>

                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-white/5 text-white/70">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Item #</th>
                                        <th class="px-4 py-3 text-left">Mean</th>
                                        <th class="px-4 py-3 text-left">SD</th>
                                        <th class="px-4 py-3 text-left">Item–Total r</th>
                                        <th class="px-4 py-3 text-left">α if deleted</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/10">
                                    @forelse(($r['items'] ?? []) as $it)
                                        <tr>
                                            <td class="px-4 py-3">{{ $it['question_no'] }}</td>
                                            <td class="px-4 py-3">{{ $it['mean'] ?? '—' }}</td>
                                            <td class="px-4 py-3">{{ $it['sd'] ?? '—' }}</td>
                                            <td class="px-4 py-3">{{ $it['rit'] ?? '—' }}</td>
                                            <td class="px-4 py-3">{{ $it['alpha_if_deleted'] ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-4 text-white/60">
                                                Not enough complete numeric data to compute item diagnostics (need at least 3 complete submissions).
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <p class="mt-3 text-xs text-white/55">
                            Notes: Item–Total r uses total-minus-item. Values closer to 1 indicate stronger alignment with the dimension score.
                            “α if deleted” helps spot items that reduce internal consistency.
                        </p>
                    </details>
                @endforeach
            </div>
        </section>

        <!-- Recent submissions + view answers -->
        <section class="mt-10">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold">Recent Submissions</h2>
                <a href="{{ route('admin.submissions') }}"
                   class="text-sm text-white/70 hover:text-white transition">
                    View all →
                </a>
            </div>

            <div class="mt-4 rounded-2xl border border-white/10 bg-white/5 divide-y divide-white/10">
                @forelse($recentSubmissions as $sub)
                    <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-white/85">
                                {{ $sub->student_id }}
                                <span class="text-white/35">•</span>
                                {{ $sub->school ?? '—' }}
                            </p>
                            <p class="mt-1 text-xs text-white/55">
                                {{ $sub->strand ?? '—' }} • {{ $sub->grade_level ?? '—' }} • {{ $sub->overall_level ?? '—' }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="text-right text-xs text-white/60">
                                {{ optional($sub->submitted_at)->format('M d, Y h:i A') }}
                            </div>

                            <a href="{{ route('admin.submissions.show', $sub->id) }}"
                               class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-xs text-white/80 hover:bg-white/10 transition">
                                View Answers
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-4 text-sm text-white/60">No submissions found for the current filters.</div>
                @endforelse
            </div>
        </section>

    </main>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartPayload = @json($chart);

        // 1) Overall donut
        new Chart(document.getElementById('overallDonut'), {
            type: 'doughnut',
            data: {
                labels: chartPayload.overallLabels,
                datasets: [{
                    data: chartPayload.overallData
                }]
            },
            options: {
                plugins: {
                    legend: { labels: { color: '#ffffff' } }
                }
            }
        });

        // 2) Dimension bar
        new Chart(document.getElementById('dimensionBar'), {
            type: 'bar',
            data: {
                labels: chartPayload.dimLabels,
                datasets: [{
                    label: 'Average Score (out of 15)',
                    data: chartPayload.dimData
                }]
            },
            options: {
                scales: {
                    x: { ticks: { color: '#ffffff' } },
                    y: { ticks: { color: '#ffffff' }, beginAtZero: true, suggestedMax: 15 }
                },
                plugins: {
                    legend: { labels: { color: '#ffffff' } }
                }
            }
        });

        // 3) School bar
        new Chart(document.getElementById('schoolBar'), {
            type: 'bar',
            data: {
                labels: chartPayload.schoolLabels,
                datasets: [{
                    label: 'Submissions',
                    data: chartPayload.schoolData
                }]
            },
            options: {
                scales: {
                    x: { ticks: { color: '#ffffff' } },
                    y: { ticks: { color: '#ffffff' }, beginAtZero: true }
                },
                plugins: {
                    legend: { labels: { color: '#ffffff' } }
                }
            }
        });
    </script>
</body>
</html>
