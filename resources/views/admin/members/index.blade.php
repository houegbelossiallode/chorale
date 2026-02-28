@extends('layouts.admin')

@section('page_title', 'Gestion des Membres')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h3 class="text-xl sm:text-2xl font-semibold text-[#444050]">Liste des Choristes</h3>
        <a href="{{ route('admin.members.create') }}" class="btn-primary gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajouter un Membre
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-[#DFF7E9] border border-[#28C76F]/20 text-[#28C76F] rounded-xl font-semibold text-sm animate-fade-in">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-100 shadow-material overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar-slim">
            <table class="w-full text-left border-collapse min-w-[600px] sm:min-w-full">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-4 sm:px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Membre</th>
                        <th class="px-4 sm:px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest hidden md:table-cell">Pupitre</th>
                        <th class="px-4 sm:px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest hidden lg:table-cell">Rôle</th>
                        <!-- <th class="px-4 sm:px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Statut</th> -->
                        <th class="px-4 sm:px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($members as $member)
                    <tr class="hover:bg-slate-50/50 transition duration-300">
                        <td class="px-4 sm:px-8 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg overflow-hidden border border-slate-100 shrink-0 shadow-sm">
                                    <img src="{{ $member->photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($member->first_name).'&background=f8f7fa&color=7367f0' }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-[#444050] text-sm truncate">{{ $member->first_name }} {{ $member->last_name }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">{{ $member->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-8 py-4 hidden md:table-cell">
                            <span class="px-3 py-1 bg-[#E7E7FF] text-[#7367F0] rounded-full text-[10px] font-bold uppercase tracking-wider">
                                {{ $member->pupitre->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-8 py-4 hidden lg:table-cell">
                            <p class="text-sm font-medium text-slate-600 capitalize">{{ $member->role->libelle }}</p>
                        </td>
                        <!-- <td class="px-4 sm:px-8 py-4">
                            <form action="{{ route('admin.members.toggle', $member) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 group">
                                    <div class="w-2.5 h-2.5 rounded-full {{ $member->is_active ? 'bg-[#28C76F] shadow-[0_0_8px_rgba(40,199,111,0.4)]' : 'bg-slate-300' }}"></div>
                                    <span class="text-[10px] font-bold {{ $member->is_active ? 'text-[#28C76F]' : 'text-slate-400' }} uppercase tracking-wider group-hover:underline">
                                        {{ $member->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </button>
                            </form>
                        </td> -->
                        <td class="px-4 sm:px-8 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.members.edit', $member) }}" class="btn-icon">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                <form action="{{ route('admin.members.destroy', $member) }}" method="POST" onsubmit="return confirm('Supprimer ce choriste ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-danger">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-slate-400 italic font-medium">Aucun choriste répertorié.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            {{ $members->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
