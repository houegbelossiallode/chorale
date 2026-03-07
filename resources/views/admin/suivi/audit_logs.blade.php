@extends('layouts.admin')

@section('page_title', 'Piste d\'Audit')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl sm:text-2xl font-semibold text-[#444050]">Suivi des Activités</h3>
                @if(isset($filteredUser))
                    <div class="mt-2 flex items-center gap-2">
                        <span class="px-3 py-1 bg-[#7367F0]/10 text-[#7367F0] rounded-full text-xs font-bold flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Filtre : {{ $filteredUser->first_name }} {{ $filteredUser->last_name }}
                        </span>
                        <a href="{{ route('admin.audit-logs.index') }}" class="text-[10px] text-slate-400 font-bold uppercase hover:text-[#7367F0] transition-colors">
                            Réinitialiser
                        </a>
                    </div>
                @else
                    <p class="text-sm text-slate-400">Historique des connexions et déconnexions</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 shadow-material overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar-slim">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Date &
                                Heure</th>
                            <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Membre</th>
                            <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Événement
                            </th>
                            <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Adresse IP
                            </th>
                            <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Appareil /
                                Navigateur</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/50 transition duration-300">
                                <td class="px-8 py-4 whitespace-nowrap">
                                    <p class="text-sm font-bold text-[#444050]">{{ $log->created_at->format('d/m/Y') }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $log->created_at->format('H:i:s') }}</p>
                                </td>
                                <td class="px-8 py-4">
                                    @if($log->user)
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-[#7367F0]/10 flex items-center justify-center text-[#7367F0] font-bold text-xs">
                                                {{ substr($log->user->first_name, 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-bold text-[#444050] text-sm truncate">{{ $log->user->name }}</p>
                                                <p class="text-[10px] text-slate-400 truncate">
                                                    {{ $log->user->role->libelle ?? 'Membre' }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">Utilisateur inconnu / supprimé</span>
                                    @endif
                                </td>
                                <td class="px-8 py-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        @if($log->event === 'login') bg-emerald-100 text-emerald-600
                                        @elseif($log->event === 'logout') bg-slate-100 text-slate-600
                                        @elseif($log->event === 'failed_login') bg-rose-100 text-rose-600
                                        @endif">
                                        {{ $log->event === 'login' ? 'Connexion' : ($log->event === 'logout' ? 'Déconnexion' : 'Échec Connexion') }}
                                    </span>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="text-xs font-mono text-slate-500">{{ $log->ip_address }}</span>
                                </td>
                                <td class="px-8 py-4">
                                    <p class="text-[10px] text-slate-400 truncate max-w-xs" title="{{ $log->user_agent }}">
                                        {{ Str::limit($log->user_agent, 50) }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center text-slate-400 italic font-medium">Aucun log
                                    d'audit enregistré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection