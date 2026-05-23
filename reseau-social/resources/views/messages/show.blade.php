@extends('layouts.app')

@section('title', 'Conversation avec ' . $user->name)

@section('content')
<div class="grid gap-6 xl:grid-cols-[1fr_2fr]">
    <aside class="card p-6">
        <h2 class="text-lg font-semibold text-slate-900">Conversation</h2>
        <div class="mt-4 space-y-4">
            <div class="rounded-3xl border border-slate-100 bg-sky-50/80 p-4">
                <p class="text-sm text-slate-700">Vous échangez avec :</p>
                <p class="mt-2 text-xl font-semibold text-slate-900">{{ $user->name }}</p>
                <p class="text-sm text-slate-500">{{ $user->email }}</p>
            </div>
        </div>
    </aside>

    <div class="card p-6">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Discussion</h1>
                <p class="mt-1 text-sm text-slate-600">Derniers messages avec {{ $user->name }}.</p>
            </div>
        </div>

        <div class="space-y-4 card-soft p-4">
            @forelse($messages as $message)
                <div class="rounded-3xl p-4 shadow-sm {{ $message->sender_id === auth()->id() ? 'bg-amber-50 self-end text-right' : 'bg-white' }}">
                    <div class="flex items-center justify-between gap-3 text-sm text-slate-500">
                        <span>{{ $message->sender_id === auth()->id() ? 'Vous' : $message->sender->name }}</span>
                        <span>{{ $message->created_at->format('d/m H:i') }}</span>
                    </div>
                    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $message->content }}</p>
                </div>
            @empty
                <p class="text-sm text-slate-500">Aucun message échangé pour le moment.</p>
            @endforelse
        </div>

        <form action="{{ route('messages.store', $user->id) }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <textarea name="content" rows="4" placeholder="Écrire un message..." class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-amber-400 focus:ring-2 focus:ring-amber-100"></textarea>
            <button type="submit" class="btn-primary">Envoyer</button>
        </form>
    </div>
</div>
@endsection
