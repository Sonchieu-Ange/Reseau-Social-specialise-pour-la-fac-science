@extends('layouts.app')

@section('title', 'Signalements')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold text-slate-900">Modération des signalements</h1>
        <p class="mt-2 text-sm text-slate-600">Gestion des contenus signalés par les étudiants.</p>
    </div>

    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 font-semibold text-slate-700">Contenu</th>
                    <th class="px-6 py-4 font-semibold text-slate-700">Signalé par</th>
                    <th class="px-6 py-4 font-semibold text-slate-700">Raison</th>
                    <th class="px-6 py-4 font-semibold text-slate-700">Statut</th>
                    <th class="px-6 py-4 font-semibold text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($reports as $report)
                    <tr>
                        <td class="px-6 py-4">{{ $report->getReportableTypeLabel() }} #{{ $report->reportable_id }}</td>
                        <td class="px-6 py-4">{{ $report->reporter->name ?? '—' }}</td>
                        <td class="px-6 py-4">{{ $report->getReasonLabel() }}</td>
                        <td class="px-6 py-4">{{ ucfirst($report->status) }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="rounded-full bg-green-600 px-4 py-2 text-xs font-semibold text-white hover:bg-green-500">Résoudre</button>
                            </form>
                            <form action="{{ route('admin.reports.reject', $report->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-500">Rejeter</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Aucun signalement en attente.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $reports->links() }}</div>
</div>
@endsection
