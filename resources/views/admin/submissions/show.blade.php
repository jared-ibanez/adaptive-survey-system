<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Submission Answers - Admin</title>
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen bg-slate-950 text-white">
    @php
        $strengths = $strengths ?? [];
        $areasForGrowth = $areasForGrowth ?? [];
        $dimensionDescriptors = $dimensionDescriptors ?? [];
        $overall = $overall ?? ['data' => [], 'score' => null, 'band' => null];
    @endphp
<main class="mx-auto max-w-6xl px-6 py-10">

    <!-- Header -->
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-[11px] uppercase tracking-wider text-white/50">Submission Details</p>
            <h1 class="text-2xl font-bold">{{ $student->student_id }}</h1>
            <p class="mt-1 text-sm text-white/70">
                {{ $student->school ?? '—' }} • {{ $student->strand ?? '—' }} • {{ $student->grade_level ?? '—' }}
            </p>
        </div>

        <a href="{{ route('admin.submissions') }}"
           class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-white/80 hover:bg-white/10 transition">
            ← Back
        </a>
    </div>

    <!-- Overall + Total -->
    <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-5">
        <p class="text-sm text-white/70">
            Overall Level:
            <span class="font-semibold text-white/85">{{ $submission->overall_level ?? '—' }}</span>
            <span class="text-white/35">•</span>
            Total Score:
            <span class="font-semibold text-white/85">{{ $submission->total_score ?? '—' }}</span>
            <span class="text-white/35">•</span>
            Submitted:
            <span class="font-semibold text-white/85">{{ optional($submission->submitted_at)->format('M d, Y h:i A') ?? '—' }}</span>
        </p>
    </div>

    <!-- ✅ Interpretation Block (same as student thank-you, but admin can see scores too) -->
    <section class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <p class="text-xs uppercase tracking-wider text-white/50">Interpretation Summary</p>

        <!-- Overall Interpretation -->
        <div class="mt-4 rounded-2xl border border-white/10 bg-slate-900/30 p-5">
            <p class="text-[11px] uppercase tracking-wider text-white/50">Overall Interpretation</p>
            <p class="mt-1 text-xl font-extrabold text-white/90">
                {{ $overall['data']['label'] ?? ($submission->overall_level ?? '—') }}
            </p>
            <p class="mt-1 text-sm text-white/70">{{ $overall['data']['title'] ?? '' }}</p>

            <p class="mt-4 text-sm text-white/75 leading-relaxed">
                {{ $overall['data']['text'] ?? '' }}
            </p>
        </div>

        <!-- Strengths & Areas -->
        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                <p class="text-xs uppercase tracking-wider text-white/50">Strengths</p>
                <p class="mt-1 text-lg font-bold text-white/90">Top 2 dimensions</p>

                <div class="mt-4 space-y-3">
                    @foreach ($strengths as $s)
                        <div class="rounded-2xl border border-white/10 bg-slate-900/30 p-4">
                            <p class="text-sm font-semibold text-white/90">
                                {{ $s['meta']['label'] ?? '' }}
                                <span class="text-white/35">•</span>
                                <span class="text-white/70">{{ $s['data']['label'] ?? '' }}</span>
                                <span class="text-white/35">•</span>
                                <span class="text-white/75">Score: {{ $s['score'] }}/15</span>
                            </p>
                            <p class="mt-1 text-sm text-white/70">{{ $s['data']['title'] ?? '' }}</p>
                            <p class="mt-3 text-sm text-white/75 leading-relaxed">{{ $s['data']['text'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                <p class="text-xs uppercase tracking-wider text-white/50">Areas for Growth</p>
                <p class="mt-1 text-lg font-bold text-white/90">Bottom 2 dimensions</p>

                <div class="mt-4 space-y-3">
                    @foreach ($areasForGrowth as $g)
                        <div class="rounded-2xl border border-white/10 bg-slate-900/30 p-4">
                            <p class="text-sm font-semibold text-white/90">
                                {{ $g['meta']['label'] ?? '' }}
                                <span class="text-white/35">•</span>
                                <span class="text-white/70">{{ $g['data']['label'] ?? '' }}</span>
                                <span class="text-white/35">•</span>
                                <span class="text-white/75">Score: {{ $g['score'] }}/15</span>
                            </p>
                            <p class="mt-1 text-sm text-white/70">{{ $g['data']['title'] ?? '' }}</p>
                            <p class="mt-3 text-sm text-white/75 leading-relaxed">{{ $g['data']['text'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- All Dimension Interpretations -->
        <div class="mt-8">
            <p class="text-xs uppercase tracking-wider text-white/50">All Dimension Interpretations</p>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                @foreach ($dimensionDescriptors as $dim)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <p class="text-[11px] uppercase tracking-wider text-white/50">
                            {{ $dim['meta']['label'] ?? 'Dimension' }}
                            <span class="text-white/35">•</span>
                            Items {{ $dim['meta']['items'] ?? '—' }}
                        </p>

                        <p class="mt-1 text-base font-bold text-white/90">
                            {{ $dim['data']['label'] ?? '' }}
                            <span class="text-white/35">•</span>
                            <span class="text-white/80">{{ $dim['data']['title'] ?? '' }}</span>
                        </p>

                        <p class="mt-2 text-xs text-white/60">
                            Score: <span class="font-semibold text-white/80">{{ $dim['score'] }}</span>/15
                        </p>

                        <p class="mt-3 text-sm text-white/75 leading-relaxed">
                            {{ $dim['data']['text'] ?? '' }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Raw Answers -->
    <div class="mt-10 space-y-5">
        <p class="text-xs uppercase tracking-wider text-white/50">Raw Answers</p>

        @foreach($groups as $title => $items)
            <section class="rounded-2xl border border-white/10 bg-white/5 p-5">
                <h2 class="text-lg font-bold">{{ $title }}</h2>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-white/5 text-white/70">
                            <tr>
                                <th class="px-4 py-3 text-left">Item #</th>
                                <th class="px-4 py-3 text-left">Score</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($items as $a)
                                <tr>
                                    <td class="px-4 py-3">{{ $a->question_no }}</td>
                                    <td class="px-4 py-3">{{ $a->score ?? 'Custom' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-white/60">No answers found for this section.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach
    </div>

</main>
</body>
</html>
