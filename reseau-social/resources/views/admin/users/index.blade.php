@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="space-y-6">
    <div class="rounded-[2rem] border border-amber-200 bg-white p-6 shadow-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Gestion des utilisateurs</h1>
                <p class="mt-2 text-sm text-slate-600">Consultez et modérez les comptes étudiants et enseignants.</p>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-700">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Nom</th>
                    <th class="px-6 py-4 text-left font-semibold">Email</th>
                    <th class="px-6 py-4 text-left font-semibold">Rôle</th>
                    <th class="px-6 py-4 text-left font-semibold">Statut</th>
                    <th class="px-6 py-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 text-slate-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ ucfirst($user->role) }}</td>
                        <td class="px-6 py-4">
                            @if($user->is_active)
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-semibold text-emerald-700">Actif</span>
                            @else
                                <span class="rounded-full bg-rose-100 px-3 py-1 text-[11px] font-semibold text-rose-700">Désactivé</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <form action="{{ route('admin.users.validate', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn-secondary">Valider</button>
                            </form>
                            <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="reason" value="Non conforme" />
                                <button type="submit" class="rounded-full bg-amber-400 px-4 py-2 text-xs font-semibold text-slate-900 hover:bg-amber-300">Suspendre</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
@endsection
