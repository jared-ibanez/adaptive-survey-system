<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Information - Adaptive Competency Survey</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-white">
    <!-- Background (matches landing) -->
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-32 h-96 w-96 rounded-full bg-indigo-500/25 blur-3xl"></div>
        <div class="absolute top-24 -right-40 h-[28rem] w-[28rem] rounded-full bg-fuchsia-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/2 h-80 w-[40rem] -translate-x-1/2 rounded-full bg-cyan-400/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.08),transparent_55%)]"></div>
        <div class="absolute inset-0 opacity-[0.10] [background-image:linear-gradient(to_right,rgba(255,255,255,0.25)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.25)_1px,transparent_1px)] [background-size:64px_64px]"></div>
    </div>

    <main class="relative mx-auto flex min-h-screen max-w-3xl items-center px-6 py-14">
        <div class="w-full">
            <!-- Top nav -->
            <div class="mb-8 flex items-center justify-between">
                <a href="{{ route('landing') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-white/80 backdrop-blur hover:bg-white/10 transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Back
                </a>

                <div class="text-sm text-white/60">
                    Step <span class="font-semibold text-white/80">1</span> of 2
                </div>
            </div>

            <div class="rounded-3xl border border-white/10 bg-white/5 p-7 backdrop-blur">
                <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">
                    Student Information
                </h1>
                <p class="mt-2 text-sm text-white/70">
                    Please fill out the details below before taking the survey.
                </p>

                {{-- Success / validation messages --}}
                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-red-500/20 bg-red-500/10 p-4">
                        <p class="font-semibold text-red-200">Please fix the following:</p>
                        <ul class="mt-2 list-disc pl-5 text-sm text-red-200/90">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="studentInfoForm" method="POST" action="{{ route('survey.start.submit') }}" class="mt-8 space-y-6">
                    @csrf

                    <div class="grid gap-5 sm:grid-cols-2">
                        <!-- LRN/ID # -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-white/80">LRN/ID #</label>
                            <input
                                name="student_id"
                                type="text"
                                value="{{ old('student_id') }}"
                                placeholder="Enter your LRN/ID #"
                                class="w-full rounded-xl border border-white/10 bg-slate-900/40 px-4 py-3 text-white placeholder:text-white/35 outline-none focus:border-white/25 focus:ring-2 focus:ring-white/10"
                                required
                            />
                        </div>

                        <!-- STRAND -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-white/80">Strand</label>
                            <select
                                name="strand"
                                class="w-full rounded-xl border border-white/10 bg-slate-900/40 px-4 py-3 text-white outline-none focus:border-white/25 focus:ring-2 focus:ring-white/10"
                                required
                            >
                                <option value="" class="bg-slate-900">Select strand</option>

                                @php
                                    $strands = ['ABM','STEM','GAS','HUMSS','TVL','Arts & Design','Sports'];
                                @endphp

                                @foreach ($strands as $s)
                                    <option value="{{ $s }}" class="bg-slate-900" {{ old('strand') === $s ? 'selected' : '' }}>
                                        {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-white/80">Gender</label>
                            <select
                                name="gender"
                                class="w-full rounded-xl border border-white/10 bg-slate-900/40 px-4 py-3 text-white outline-none focus:border-white/25 focus:ring-2 focus:ring-white/10"
                                required
                            >
                                <option value="" class="bg-slate-900">Select gender</option>
                                <option value="Male" class="bg-slate-900" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" class="bg-slate-900" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <!-- School -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-white/80">School</label>
                            <input
                                name="school"
                                type="text"
                                value="{{ old('school') }}"
                                placeholder="Enter your school"
                                class="w-full rounded-xl border border-white/10 bg-slate-900/40 px-4 py-3 text-white placeholder:text-white/35 outline-none focus:border-white/25 focus:ring-2 focus:ring-white/10"
                                required
                            />
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs text-white/55">
                            Your answers will be used for competency scoring and reporting.
                        </p>
                        <button
                            id="proceedBtn"
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 font-semibold text-slate-900 shadow-lg shadow-white/10 transition hover:-translate-y-0.5 hover:bg-white/95 focus:outline-none focus:ring-2 focus:ring-white/30"
                        >
                            Proceed
                            <svg class="ml-2 h-5 w-5" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M13 7L18 12L13 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M6 12H18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <p class="mt-4 text-center text-xs text-white/45">
                © {{ date('Y') }} Jared was HERE
            </p>
        </div>
    </main>
</body>
</html>
