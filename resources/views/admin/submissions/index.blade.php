<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Submissions - Admin</title>
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen bg-slate-950 text-white">
<main class="mx-auto max-w-7xl px-6 py-10">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-[11px] uppercase tracking-wider text-white/50">Admin</p>
            <h1 class="text-2xl font-bold">All Submissions</h1>
            <p class="mt-1 text-sm text-white/70">Use the dashboard filters for faster analysis.</p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-white/80 hover:bg-white/10 transition">
            ← Back to Dashboard
        </a>
    </div>

    <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-5">
        <p class="text-sm text-white/70">
            Tip: Click <span class="font-semibold text-white/85">View Answers</span> to inspect any student’s responses.
        </p>
    </div>

    <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-white/5 text-white/70">
                    <tr>
                        <th class="px-4 py-3 text-left">Student ID</th>
                        <th class="px-4 py-3 text-left">School</th>
                        <th class="px-4 py-3 text-left">Strand</th>
                        <th class="px-4 py-3 text-left">Grade</th>
                        <th class="px-4 py-3 text-left">Overall</th>
                        <th class="px-4 py-3 text-left">Total</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @foreach($rows as $r)
                        <tr>
                            <td class="px-4 py-3">{{ $r->student_id }}</td>
                            <td class="px-4 py-3">{{ $r->school ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $r->strand ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $r->grade_level ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $r->overall_level ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $r->total_score ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.submissions.show', $r->id) }}"
                                   class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-xs text-white/80 hover:bg-white/10 transition">
                                    View Answers
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-4">
            {{ $rows->links() }}
        </div>
    </div>
</main>
</body>
</html>
