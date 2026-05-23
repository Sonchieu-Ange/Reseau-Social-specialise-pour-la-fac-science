@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
<div class="space-y-6">
    <div class="hero-panel">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">{{ $announcement->title }}</h1>
                <p class="mt-2 text-sm text-slate-500">Publié le {{ $announcement->published_at->format('d/m/Y') }} par {{ $announcement->admin->name ?? 'Admin' }}</p>
            </div>
            <span class="rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">{{ ucfirst($announcement->priority ?? 'normal') }}</span>
        </div>

        <div class="mt-6 space-y-4 text-slate-700 leading-7">
            <p>{{ $announcement->content }}</p>
        </div>
    </div>
</div>
@endsection
