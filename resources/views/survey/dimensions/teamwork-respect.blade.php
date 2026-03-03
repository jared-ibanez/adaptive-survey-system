<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Teamwork & Respect - Adaptive Competency Survey</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-white">
    <!-- Background -->
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-32 h-96 w-96 rounded-full bg-indigo-500/25 blur-3xl"></div>
        <div class="absolute top-24 -right-40 h-[28rem] w-[28rem] rounded-full bg-fuchsia-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/2 h-80 w-[40rem] -translate-x-1/2 rounded-full bg-cyan-400/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.08),transparent_55%)]"></div>
        <div class="absolute inset-0 opacity-[0.10] [background-image:linear-gradient(to_right,rgba(255,255,255,0.25)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.25)_1px,transparent_1px)] [background-size:64px_64px]"></div>
    </div>

    @php
        $currentDimension = 2;
        $totalDimensions = 6;
        $progressPercent = (int) round(($currentDimension / $totalDimensions) * 100);
    @endphp

    <!-- Sticky Header -->
    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/70 backdrop-blur">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 py-3">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-[11px] uppercase tracking-wider text-white/50">Adaptive Competency Survey</p>
                    <p class="text-sm font-semibold text-white/90">
                        Dimension {{ $currentDimension }} of {{ $totalDimensions }}
                    </p>
                </div>

                <a href="{{ route('survey.dimension1') }}"
                   class="shrink-0 inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs text-white/80 hover:bg-white/10 transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Back
                </a>
            </div>

            <!-- Progress -->
            <div class="mt-3">
                <div class="flex items-center justify-between text-[11px] text-white/55">
                    <span>Progress</span>
                    <span><span class="font-semibold text-white/80">{{ $progressPercent }}%</span></span>
                </div>
                <div class="mt-2 h-2 w-full overflow-hidden rounded-full border border-white/10 bg-slate-900/40">
                    <div class="h-full rounded-full bg-white/80" style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>

            <!-- Collapsible instructions -->
            <div class="mt-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                <p class="text-sm font-semibold text-white/85">
                    Instructions
                </p>

                <ol class="mt-3 list-decimal space-y-1 pl-5 text-xs text-white/75">
                    <li>Read each situation carefully.</li>
                    <li>Select the response that best describes what you would do.</li>
                    <li>There are no right or wrong answers.</li>
                    <li>Answer honestly based on what you would truly do.</li>
                </ol>
            </div>
        </div>
    </header>

    <main class="relative mx-auto max-w-5xl px-4 sm:px-6 py-8">
        <!-- Student info (collapsible) -->
        <details class="rounded-3xl border border-white/10 bg-white/5 p-4 sm:p-5 backdrop-blur">
            <summary class="cursor-pointer list-none select-none">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[11px] uppercase tracking-wider text-white/50">Student</p>
                        <p class="mt-1 text-sm font-semibold text-white/90">
                            ID/LRN: <span class="font-bold">{{ $student->student_id }}</span>
                            <span class="text-white/45">•</span>
                            <span class="text-white/75">{{ $student->grade_level }}</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-2 text-[11px] text-white/60">
                        <span class="rounded-full border border-white/10 bg-slate-900/30 px-3 py-1">Items 6–10</span>
                        <span class="rounded-full border border-white/10 bg-slate-900/30 px-3 py-1">Teamwork & Respect</span>
                        <span class="ml-1 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-white/70">Details ▾</span>
                    </div>
                </div>
            </summary>

            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                <div class="rounded-2xl border border-white/10 bg-slate-900/30 px-4 py-3">
                    <p class="text-[11px] uppercase tracking-wider text-white/50 leading-tight">Gender</p>
                    <p class="mt-1 text-sm font-semibold text-white/85 leading-relaxed">{{ $student->gender }}</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-900/30 px-4 py-3">
                    <p class="text-[11px] uppercase tracking-wider text-white/50 leading-tight">Strand</p>
                    <p class="mt-1 text-sm font-semibold text-white/85 leading-relaxed">{{ $student->strand }}</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-900/30 px-4 py-3">
                    <p class="text-[11px] uppercase tracking-wider text-white/50 leading-tight">Dimension</p>
                    <p class="mt-1 text-sm font-semibold text-white/85 leading-relaxed">2</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-900/30 px-4 py-3">
                    <p class="text-[11px] uppercase tracking-wider text-white/50 leading-tight">Status</p>
                    <p class="mt-1 text-sm font-semibold text-white/85 leading-relaxed">In Progress</p>
                </div>
            </div>
        </details>

        <!-- Dimension title -->
        <section class="mt-8">
            <p class="text-xs uppercase tracking-wider text-white/50">Dimension 2</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight sm:text-3xl">Teamwork & Respect</h2>
            <p class="mt-2 text-sm text-white/65">Choose the option that best describes what you would do.</p>
        </section>

        <form method="POST" action="{{ route('survey.dimension2.save') }}" id="dimension2Form" class="mt-6 space-y-5">
            @csrf

        @php
            $prefill = $answers ?? [];

            $questions = [
                6  => "When a classmate accidentally bumps me, I…",
                7  => "When a groupmate struggles to understand, I…",
                8  => "When a friend arrives late for a scheduled group study session, I…",
                9  => "When I disagree with a classmate, I…",
                10 => "When a teacher corrects my mistake, I…",
            ];

            $choices = [
                6 => [
                    ['score' => 0, 'text' => 'react angrily.'],
                    ['score' => 3, 'text' => 'accept calmly and maintain a positive interaction.'],
                    ['score' => 1, 'text' => 'stay quiet but feel resentful.'],
                    ['score' => 2, 'text' => 'accept the apology calmly.'],
                ],
                7 => [
                    ['score' => 2, 'text' => 'explain clearly and check understanding.'],
                    ['score' => 0, 'text' => 'ignore the situation.'],
                    ['score' => 3, 'text' => 'adjust my explanation using different examples until clear.'],
                    ['score' => 1, 'text' => 'explain once without checking understanding.'],
                ],
                8 => [
                    ['score' => 0, 'text' => 'complain or blame my friend in front of others.'],
                    ['score' => 1, 'text' => 'ask briefly what happened and continue the activity.'],
                    ['score' => 3, 'text' => 'acknowledge the delay calmly and help the group refocus on the task.'],
                    ['score' => 2, 'text' => 'continue studying but ignore my friend.'],
                ],
                9 => [
                    ['score' => 1, 'text' => 'state my opinion emotionally.'],
                    ['score' => 3, 'text' => 'respectfully discuss differences and seek common ground.'],
                    ['score' => 0, 'text' => 'interrupt or dismiss their idea.'],
                    ['score' => 2, 'text' => 'share my view respectfully with reasons.'],
                ],
                10 => [
                    ['score' => 3, 'text' => 'thank the teacher and use the feedback to improve future work.'],
                    ['score' => 0, 'text' => 'ignore the feedback.'],
                    ['score' => 2, 'text' => 'accept and revise my work.'],
                    ['score' => 1, 'text' => 'feel embarrassed and avoid responding.'],
                ],
            ];
        @endphp

            @foreach ($questions as $qNo => $qText)
                @php
                    $savedScore = old("q$qNo.score", $prefill[$qNo]['score'] ?? null);
                    $savedCustom = old("q$qNo.custom_response", $prefill[$qNo]['custom_response'] ?? '');
                    $isCustomSelected = ($savedScore === 'custom');
                @endphp

                <section class="rounded-3xl border border-white/10 bg-white/5 p-4 sm:p-5 backdrop-blur">
                    <p class="text-xs uppercase tracking-wider text-white/50">Item {{ $qNo }}</p>
                    <h3 class="mt-1 text-base font-semibold text-white/90 sm:text-lg">{{ $qText }}</h3>

                    <div class="mt-4 space-y-3">
                        @foreach ($choices[$qNo] as $opt)
                            <label class="group flex cursor-pointer items-start gap-3 rounded-2xl border border-white/10 bg-slate-900/30 p-4 transition hover:bg-slate-900/45">
                                <input
                                    type="radio"
                                    name="q{{ $qNo }}[score]"
                                    value="{{ $opt['score'] }}"
                                    class="mt-1 h-4 w-4 accent-white"
                                    {{ (string)$savedScore === (string)$opt['score'] ? 'checked' : '' }}
                                    data-qno="{{ $qNo }}"
                                    data-is-custom="0"
                                    required
                                />
                                <div class="flex-1">
                                    <p class="text-sm text-white/85">{{ $opt['text'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </section>
            @endforeach

<div class="mt-10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">

                <p class="text-xs text-white/50 sm:mr-3">Saved when you click <span class="font-semibold text-white/80">Next</span>.</p>

                <button
                    id="nextBtn"
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 font-semibold text-slate-900 shadow-lg shadow-white/10 transition hover:-translate-y-0.5 hover:bg-white/95 focus:outline-none focus:ring-2 focus:ring-white/30"
                >
                    Next
                    <svg class="ml-2 h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 7L18 12L13 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6 12H18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-xs text-white/45">© {{ date('Y') }} Jared was HERE</p>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Toggle custom response textarea
            document.querySelectorAll('input[type="radio"][data-qno]').forEach((radio) => {
                radio.addEventListener('change', (e) => {
                    const qNo = e.target.getAttribute('data-qno');
                    const isCustom = e.target.getAttribute('data-is-custom') === '1';
                    const wrap = document.getElementById(`customWrap-${qNo}`);
                    if (!wrap) return;
                    wrap.classList.toggle('hidden', !isCustom);
                });
            });

            // AJAX save on Next
            const form = document.getElementById('dimension2Form');
            const btn = document.getElementById('nextBtn');
            if (!form || !btn) return;

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                btn.disabled = true;
                btn.classList.add('opacity-80','cursor-not-allowed');
                btn.innerText = 'Saving...';

                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const formData = new FormData(form);

                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData
                    });

                    const data = await res.json();

                    if (!res.ok || !data?.ok) {
                        alert(data?.message || 'Saving failed. Please try again.');
                        btn.disabled = false;
                        btn.classList.remove('opacity-80','cursor-not-allowed');
                        btn.innerText = 'Next';
                        return;
                    }

                    window.location.href = data.redirect;
                } catch (err) {
                    console.error(err);
                    alert('Network error. Please check your connection and try again.');
                    btn.disabled = false;
                    btn.classList.remove('opacity-80','cursor-not-allowed');
                    btn.innerText = 'Next';
                }
            });
        });
    </script>
</body>
</html>
