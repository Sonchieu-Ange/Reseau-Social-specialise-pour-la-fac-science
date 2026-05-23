@extends('layouts.app')

@section('title', 'Groupe ' . $group->name)

@section('content')
<div class="space-y-6">
    <div class="hero-panel">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">{{ $group->name }}</h1>
                <p class="mt-3 text-sm leading-7 text-slate-600">{{ $group->description }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <form action="{{ route('groups.addMember', $group->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary">Rejoindre</button>
                </form>
                <form action="{{ route('groups.removeMember', $group->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-secondary bg-white text-slate-900 hover:bg-slate-100">Quitter</button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
        <div class="space-y-6">
            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Membres</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    @forelse($group->members as $member)
                        <div class="rounded-3xl border border-gray-100 bg-slate-50 p-4 text-sm text-slate-700">{{ $member->name }}</div>
                    @empty
                        <div class="text-slate-500">Aucun membre pour le moment.</div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Flux du groupe</h2>
                <div class="mt-4 space-y-4">
                    @forelse($group->posts as $post)
                        @include('partials._post_card', ['post' => $post])
                    @empty
                        <p class="text-sm text-slate-500">Aucun post pour ce groupe.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="rounded-3xl border border-gray-200 bg-slate-50 p-6 shadow-sm">
                <h3 class="font-semibold text-slate-900">Type</h3>
                <p class="mt-2 text-sm text-slate-600">{{ ucfirst($group->type) }}</p>
            </div>
            <div class="rounded-3xl border border-gray-200 bg-slate-50 p-6 shadow-sm">
                <h3 class="font-semibold text-slate-900">Président</h3>
                <p class="mt-2 text-sm text-slate-600">{{ $group->president->name ?? 'Non défini' }}</p>
            </div>
        </aside>
    </div>
</div>
@endsection
