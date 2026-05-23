@extends('layouts.app')

@section('title', 'Événement')

@section('content')
<div class="space-y-6">
    <div class="hero-panel">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">{{ $event->title }}</h1>
                <p class="mt-3 text-sm leading-7 text-slate-600">{{ $event->location }} • {{ $event->event_date->format('d/m/Y H:i') }}</p>
            </div>
            <div class="status-chip">{{ ucfirst($event->status) }}</div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-[1.5fr_0.8fr]">
            <div class="space-y-5">
                <div class="rounded-3xl border border-gray-200 bg-slate-50 p-6">
                    <h2 class="font-semibold text-slate-900">Description</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-700">{{ $event->description }}</p>
                </div>
                <div class="rounded-3xl border border-gray-200 bg-slate-50 p-6">
                    <h2 class="font-semibold text-slate-900">Organisateur</h2>
                    <p class="mt-3 text-sm text-slate-700">{{ $event->organizer->name ?? '—' }}</p>
                </div>
            </div>
            <aside class="space-y-4">
                <div class="rounded-3xl border border-gray-200 bg-slate-50 p-6">
                    <h3 class="font-semibold text-slate-900">Infos pratiques</h3>
                    <ul class="mt-4 space-y-3 text-sm text-slate-700">
                        <li><strong>Lieu :</strong> {{ $event->location }}</li>
                        <li><strong>Capacité :</strong> {{ $event->capacity ?? 'Illimitée' }}</li>
                        <li><strong>Catégorie :</strong> {{ ucfirst($event->category) }}</li>
                    </ul>
                </div>
                <form action="{{ route('events.register', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full rounded-full bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-900 hover:bg-amber-300">Participer</button>
                </form>
            </aside>
        </div>
    </div>
</div>
@endsection
