<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Results - Adaptive Competency Survey</title>
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen bg-slate-950 text-white flex items-center justify-center px-4">
    <!-- Background -->
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-32 h-96 w-96 rounded-full bg-indigo-500/25 blur-3xl"></div>
        <div class="absolute top-24 -right-40 h-[28rem] w-[28rem] rounded-full bg-fuchsia-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/2 h-80 w-[40rem] -translate-x-1/2 rounded-full bg-cyan-400/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.08),transparent_55%)]"></div>
        <div class="absolute inset-0 opacity-[0.10] [background-image:linear-gradient(to_right,rgba(255,255,255,0.25)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.25)_1px,transparent_1px)] [background-size:64px_64px]"></div>
    </div>

    <main class="relative w-full max-w-3xl py-10">
        <section class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur">

            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-white/10">
                    <svg class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <p class="text-[11px] uppercase tracking-wider text-white/50">Adaptive Competency Survey</p>
                <h1 class="mt-2 text-2xl font-extrabold tracking-tight sm:text-3xl">
                    Your Results Summary
                </h1>
                <p class="mt-3 text-sm text-white/70 leading-relaxed">
                    Below is a short interpretation of your results based on your responses.
                </p>
            </div>

            <!-- Overall Interpretation -->
            <div class="mt-8 rounded-2xl border border-white/10 bg-slate-900/30 p-6">
                <p class="text-[11px] uppercase tracking-wider text-white/50">Overall Level</p>
                <p class="mt-1 text-xl font-extrabold text-white/90">
                    {{ $overall['data']['label'] ?? '—' }}
                </p>
                <p class="mt-1 text-sm text-white/70">
                    {{ $overall['data']['title'] ?? '' }}
                </p>

                <p class="mt-4 text-sm text-white/75 leading-relaxed">
                    {{ $overall['data']['text'] ?? '' }}
                </p>

                <p class="mt-4 text-xs text-white/55">
                    Note: Individual scores are not shown to students. This is a descriptive interpretation.
                </p>
            </div>

            <!-- ✅ Strengths & Areas for Growth -->
            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <!-- Strengths -->
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                    <p class="text-xs uppercase tracking-wider text-white/50">Strengths</p>
                    <h2 class="mt-1 text-lg font-bold text-white/90">Your strongest areas</h2>
                    <p class="mt-2 text-sm text-white/65">Based on your top 2 dimension scores.</p>

                    <div class="mt-4 space-y-3">
                        @foreach ($strengths as $s)
                            <div class="rounded-2xl border border-white/10 bg-slate-900/30 p-4">
                                <p class="text-sm font-semibold text-white/90">
                                    {{ $s['meta']['label'] ?? '' }}
                                    <span class="text-white/35">—</span>
                                    <span class="text-white/75">{{ $s['data']['title'] ?? '' }}</span>
                                </p>
                                <p class="mt-2 text-sm text-white/70 leading-relaxed">
                                    {{ $s['data']['text'] ?? '' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Areas for Growth -->
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                    <p class="text-xs uppercase tracking-wider text-white/50">Areas for Growth</p>
                    <h2 class="mt-1 text-lg font-bold text-white/90">Areas to improve</h2>
                    <p class="mt-2 text-sm text-white/65">Based on your lowest 2 dimension scores.</p>

                    <div class="mt-4 space-y-3">
                        @foreach ($areasForGrowth as $g)
                            <div class="rounded-2xl border border-white/10 bg-slate-900/30 p-4">
                                <p class="text-sm font-semibold text-white/90">
                                    {{ $g['meta']['label'] ?? '' }}
                                    <span class="text-white/35">—</span>
                                    <span class="text-white/75">{{ $g['data']['title'] ?? '' }}</span>
                                </p>
                                <p class="mt-2 text-sm text-white/70 leading-relaxed">
                                    {{ $g['data']['text'] ?? '' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Dimension Interpretations -->
            <div class="mt-10">
                <p class="text-xs uppercase tracking-wider text-white/50">All Dimension Interpretations</p>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    @foreach ($dimensionDescriptors as $dim)
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                            <p class="text-[11px] uppercase tracking-wider text-white/50">
                                {{ $dim['meta']['label'] ?? 'Dimension' }}
                                <span class="text-white/35">•</span>
                                Items {{ $dim['meta']['items'] ?? '' }}
                            </p>

                            <p class="mt-1 text-base font-bold text-white/90">
                                {{ $dim['data']['label'] ?? '' }}
                                <span class="text-white/35">—</span>
                                <span class="text-white/80">{{ $dim['data']['title'] ?? '' }}</span>
                            </p>

                            <p class="mt-3 text-sm text-white/75 leading-relaxed">
                                {{ $dim['data']['text'] ?? '' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Thank you at the end -->
            <div class="mt-10 text-center">
                <h2 class="text-lg font-extrabold text-white/90">Thank you!</h2>
                <p class="mt-2 text-sm text-white/75 leading-relaxed">
                    Your responses have been successfully submitted. We appreciate your honesty and time in completing the survey.
                </p>

                <div class="mt-6 flex flex-col gap-3">
                    <a href="{{ route('landing') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 font-semibold text-slate-900 shadow-lg shadow-white/10 transition hover:-translate-y-0.5 hover:bg-white/95 focus:outline-none focus:ring-2 focus:ring-white/30">
                        Return to Home
                    </a>
                </div>

                <p class="mt-3 text-xs text-white/55">
                    © {{ date('Y') }} Adaptive Competency Survey
                </p>
            </div>

        </section>
    </main>
</body>
</html>
