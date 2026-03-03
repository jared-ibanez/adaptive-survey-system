<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login - Adaptive Competency Survey</title>
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

    <main class="relative mx-auto flex min-h-screen max-w-md items-center px-6 py-14">
        <div class="w-full rounded-3xl border border-white/10 bg-white/5 p-7 backdrop-blur">
            <p class="text-[11px] uppercase tracking-wider text-white/50">Admin Portal</p>
            <h1 class="mt-2 text-2xl font-bold tracking-tight sm:text-3xl">Sign in</h1>
            <p class="mt-2 text-sm text-white/70">
                Login using your admin email and password.
            </p>

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

            <form method="POST" action="{{ route('admin.login.submit') }}" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-medium text-white/80">Email</label>
                    <input
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email"
                        class="w-full rounded-xl border border-white/10 bg-slate-900/40 px-4 py-3 text-white placeholder:text-white/35 outline-none focus:border-white/25 focus:ring-2 focus:ring-white/10"
                        required
                    />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-white/80">Password</label>
                    <input
                        name="password"
                        type="password"
                        placeholder="Enter your password"
                        class="w-full rounded-xl border border-white/10 bg-slate-900/40 px-4 py-3 text-white placeholder:text-white/35 outline-none focus:border-white/25 focus:ring-2 focus:ring-white/10"
                        required
                    />
                </div>

                <div class="mt-10 flex flex-col gap-3">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 font-semibold text-slate-900 shadow-lg shadow-white/10 transition hover:-translate-y-0.5 hover:bg-white/95 focus:outline-none focus:ring-2 focus:ring-white/30"
                    >
                        Login
                        <svg class="ml-2 h-5 w-5" viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 7L18 12L13 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M6 12H18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </button>

                    <a href="{{ route('landing') }}"
                       class="text-center text-sm text-white/70 hover:text-white transition">
                        Back to Home
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
