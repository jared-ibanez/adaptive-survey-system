<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Adaptive Competency Survey</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-white">
    <!-- Decorative background -->
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-32 h-96 w-96 rounded-full bg-indigo-500/25 blur-3xl"></div>
        <div class="absolute top-24 -right-40 h-[28rem] w-[28rem] rounded-full bg-fuchsia-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/2 h-80 w-[40rem] -translate-x-1/2 rounded-full bg-cyan-400/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.08),transparent_55%)]"></div>
        <div class="absolute inset-0 opacity-[0.10] [background-image:linear-gradient(to_right,rgba(255,255,255,0.25)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.25)_1px,transparent_1px)] [background-size:64px_64px]"></div>
    </div>

    <main class="relative mx-auto flex min-h-screen max-w-6xl items-center px-6 py-14">
        <div class="grid w-full gap-10 lg:grid-cols-2 lg:items-center">
            <!-- Left: Copy -->
            <section>
                <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-white/80 backdrop-blur">
                    <span class="inline-block h-2 w-2 rounded-full bg-emerald-400"></span>
                    Quick • Simple • Confidential
                </div>

                <h1 class="mt-6 text-4xl font-extrabold tracking-tight sm:text-5xl">
                    ADAPTIVE<br class="hidden sm:block" />
                    <span class="bg-gradient-to-r from-indigo-300 via-white to-fuchsia-200 bg-clip-text text-transparent">
                        COMPETENCY SURVEY
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-white/75 sm:text-lg">
                    Answer a short set of real-life situations to understand strengths in decision-making,
                    teamwork, learning skills, responsibility, flexible thinking, and critical & creative thinking.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('survey.start') }}"
                       class="group inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 font-semibold text-slate-900 shadow-lg shadow-white/10 transition hover:-translate-y-0.5 hover:bg-white/95 focus:outline-none focus:ring-2 focus:ring-white/30">
                        Take Survey
                        <svg class="ml-2 h-5 w-5 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 7L18 12L13 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round" />
                            <path d="M6 12H18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </a>

                    <div class="text-sm text-white/60">
                        Takes about <span class="font-semibold text-white/80">5–8 minutes</span>
                        • No special preparation needed.
                    </div>
                </div>

                <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                        <p class="text-xs uppercase tracking-wider text-white/50">Questions</p>
                        <p class="mt-1 text-2xl font-bold">30</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                        <p class="text-xs uppercase tracking-wider text-white/50">Dimensions</p>
                        <p class="mt-1 text-2xl font-bold">6</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                        <p class="text-xs uppercase tracking-wider text-white/50">Scoring</p>
                        <p class="mt-1 text-2xl font-bold">Auto</p>
                    </div>
                </div>
            </section>

            <!-- Right: Card -->
            <section class="lg:justify-self-end">
                <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-7 backdrop-blur">
                    <div class="absolute -top-20 -right-20 h-56 w-56 rounded-full bg-indigo-400/20 blur-2xl"></div>
                    <div class="absolute -bottom-24 -left-16 h-56 w-56 rounded-full bg-fuchsia-400/15 blur-2xl"></div>

                    <h2 class="text-lg font-bold">What you’ll get</h2>
                    <p class="mt-2 text-sm text-white/70">
                        After submitting, you’ll receive scores and interpretation for each dimension plus an overall adaptive competency level.
                    </p>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="mt-1 h-9 w-9 rounded-xl bg-white/10 flex items-center justify-center border border-white/10">
                                <span class="text-lg">🧠</span>
                            </div>
                            <div>
                                <p class="font-semibold">Clear results</p>
                                <p class="text-sm text-white/65">Dimension breakdown + overall interpretation.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="mt-1 h-9 w-9 rounded-xl bg-white/10 flex items-center justify-center border border-white/10">
                                <span class="text-lg">📊</span>
                            </div>
                            <div>
                                <p class="font-semibold">Admin-ready</p>
                                <p class="text-sm text-white/65">Designed to store results for dashboards and summaries.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="mt-1 h-9 w-9 rounded-xl bg-white/10 flex items-center justify-center border border-white/10">
                                <span class="text-lg">🔒</span>
                            </div>
                            <div>
                                <p class="font-semibold">Respectful & confidential</p>
                                <p class="text-sm text-white/65">Encourages honest answers without pressure.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                        <p class="text-sm text-white/75">
                            Tip: Answer based on what you would truly do. If your response differs, use the “I would respond differently” option.
                        </p>
                    </div>
                </div>

                <p class="mt-4 text-center text-xs text-white/45 lg:text-right">
                    © {{ date('Y') }} Jared was HERE
                </p>
            </section>
        </div>
    </main>
</body>
</html>
