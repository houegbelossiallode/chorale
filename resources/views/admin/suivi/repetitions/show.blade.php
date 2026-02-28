@extends('layouts.admin')

@section('title', 'Faire l\'appel')

@section('content')
<div class="space-y-6" x-data="{ 
    showProgramModal: false,
    showMotifModal: false,
    currentUserId: null,
    currentStatus: null,
    currentMotif: '',
    selectedChants: {{ json_encode($repetition->chants->pluck('id')) }},
    presences: {{ json_encode($members->mapWithKeys(function($m) { 
        $presence = $m->presences->first();
        return [$m->id => [
            'status' => $presence?->status,
            'motif' => $presence?->motif
        ]]; 
    })) }},
    stats: {
        present: {{ $repetition->presences()->where('status', 'present')->count() }},
        all: {{ $members->count() }}
    },
    async updatePresence(userId, status) {
        if (status === 'absent' || status === 'justifie') {
            this.currentUserId = userId;
            this.currentStatus = status;
            this.currentMotif = this.presences[userId].motif || '';
            this.showMotifModal = true;
            return;
        }
        await this.confirmPresence(userId, status, null);
    },
    async confirmPresence(userId, status, motif) {
        const oldStatus = this.presences[userId].status;
        const oldMotif = this.presences[userId].motif;
        
        if (oldStatus === status && oldMotif === motif) return;

        this.presences[userId].status = status;
        this.presences[userId].motif = motif;
        
        // Update local stats
        if (oldStatus === 'present') this.stats.present--;
        if (status === 'present') this.stats.present++;

        try {
            const response = await fetch('{{ route('admin.presences.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    user_id: userId,
                    repetition_id: {{ $repetition->id }},
                    status: status,
                    motif: motif
                })
            });

            if (!response.ok) throw new Error();
        } catch (e) {
            // Rollback on error
            this.presences[userId].status = oldStatus;
            this.presences[userId].motif = oldMotif;
            if (oldStatus === 'present') this.stats.present++;
            if (status === 'present') this.stats.present--;
            alert('Erreur lors de l’enregistrement de la présence.');
        }
    }
}">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <a href="{{ route('admin.repetitions.index') }}" class="text-sm text-[#7367F0] flex items-center gap-2 mb-2 hover:underline font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour au planning
            </a>
            <h1 class="text-2xl font-black text-[#444050] uppercase tracking-tight">Feuille d'appel : {{ $repetition->titre }}</h1>
            <div class="flex items-center gap-4 mt-1">
                <p class="text-[#7367F0] font-bold text-sm flex items-center gap-1.5 uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ \Carbon\Carbon::parse($repetition->start_time)->format('d F Y') }}
                </p>
                <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                <p class="text-slate-500 text-sm flex items-center gap-1.5 font-medium">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ \Carbon\Carbon::parse($repetition->start_time)->format('H:i') }} — {{ \Carbon\Carbon::parse($repetition->end_time)->format('H:i') }}
                </p>
                <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                <p class="text-slate-500 text-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $repetition->lieu }}
                </p>
                <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                <p class="text-slate-500 text-sm flex items-center gap-1.5 italic">
                    {{ $repetition->description ?: 'Pas de description supplémentaire' }}
                </p>
            </div>
        </div>
        <div class="flex gap-3">
            <button @click="showProgramModal = true" class="btn-primary-outline flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                Gérer le Programme
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-[#DFF7E9] border border-[#28C76F]/20 text-[#28C76F] rounded-xl font-semibold text-sm animate-fade-in flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Attendance Table -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-material border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                    <h3 class="font-bold text-[#444050] flex items-center gap-2">
                        <span class="w-2 h-5 bg-[#7367F0] rounded-full"></span>
                        Pointage des présences
                    </h3>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest"><span x-text="stats.all"></span> Choristes attendus</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 text-[#444050] text-[11px] uppercase tracking-widest font-bold border-b border-gray-100">
                                <th class="px-8 py-4">Membre</th>
                                <th class="px-8 py-4">Pupitre</th>
                                <th class="px-8 py-4 text-center">Présence</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-[14px]">
                            @foreach($members as $member)
                            <tr class="hover:bg-gray-50/20 transition-colors group">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg overflow-hidden border border-slate-100 shadow-sm">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($member->first_name.' '.$member->last_name) }}&background=f8f7fa&color=7367f0" alt="" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#444050] group-hover:text-[#7367F0] transition-colors uppercase tracking-tight">{{ $member->first_name }} {{ $member->last_name }}</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $member->email }}</p>
                                            <template x-if="presences[{{ $member->id }}].motif">
                                                <div class="mt-1 flex items-center gap-1.5 text-[10px] text-[#FF9F43] font-bold uppercase italic bg-[#FF9F43]/5 px-2 py-0.5 rounded-md w-fit border border-[#FF9F43]/10">
                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    <span x-text="presences[{{ $member->id }}].motif"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold uppercase tracking-widest border border-slate-100">
                                        {{ $member->pupitre->name ?? 'NON DÉFINI' }}
                                    </span>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="flex justify-center gap-1.5">
                                        <button @click="updatePresence({{ $member->id }}, 'present')" title="Présent"
                                                class="w-9 h-9 rounded-lg flex items-center justify-center transition-all border outline-none"
                                                :class="presences[{{ $member->id }}].status === 'present' ? 'bg-[#28C76F] text-white border-[#28C76F] shadow-sm' : 'bg-white text-slate-300 border-slate-100 hover:border-[#28C76F]/30 hover:text-[#28C76F]'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                        
                                        <button @click="updatePresence({{ $member->id }}, 'absent')" title="Absent"
                                                class="w-9 h-9 rounded-lg flex items-center justify-center transition-all border outline-none"
                                                :class="presences[{{ $member->id }}].status === 'absent' ? 'bg-[#EA5455] text-white border-[#EA5455] shadow-sm' : 'bg-white text-slate-300 border-slate-100 hover:border-[#EA5455]/30 hover:text-[#EA5455]'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                        
                                        <button @click="updatePresence({{ $member->id }}, 'justifie')" title="Justifié"
                                                class="w-9 h-9 rounded-lg flex items-center justify-center transition-all border outline-none"
                                                :class="presences[{{ $member->id }}].status === 'justifie' ? 'bg-[#FF9F43] text-white border-[#FF9F43] shadow-sm' : 'bg-white text-slate-300 border-slate-100 hover:border-[#FF9F43]/30 hover:text-[#FF9F43]'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Program Musical -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-material border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="font-bold text-[#444050] flex items-center gap-2 text-sm uppercase tracking-wider">
                        <svg class="w-5 h-5 text-[#7367F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        Programme Musical
                    </h3>
                </div>
                <div class="p-6">
                    @forelse($repetition->chants as $chant)
                    <div class="flex items-center gap-4 py-3 border-b border-slate-50 last:border-0 group">
                        <div class="w-10 h-10 bg-[#7367F0]/10 rounded-xl flex items-center justify-center text-[#7367F0] group-hover:bg-[#7367F0] group-hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-[#444050] text-sm truncate">{{ $chant->title }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ $chant->composer ?? 'Compositeur inconnu' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 px-4">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <p class="text-slate-400 text-sm italic font-medium">Aucun chant n'est encore programmé pour cette séance.</p>
                        <button @click="showProgramModal = true" class="mt-4 text-[#7367F0] text-xs font-bold uppercase hover:underline">Définir le programme</button>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Stats Recap -->
            <div class="bg-gradient-to-br from-[#7367F0] to-[#9E95F5] rounded-2xl p-6 shadow-material text-white">
                <h4 class="font-bold text-sm uppercase tracking-wider mb-4 border-b border-white/20 pb-2">Résumé Rapide</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="opacity-80 uppercase font-bold tracking-widest text-[10px]">Présents</span>
                        <span class="font-black text-2xl" x-text="stats.present"></span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="opacity-80">Chants à travailler</span>
                        <span class="font-black text-lg">{{ $repetition->chants->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Musical Program Modal -->
    <div x-show="showProgramModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
         x-cloak x-transition.opacity>
        
        <div class="bg-white rounded-3xl w-full max-w-2xl shadow-material-lg overflow-hidden flex flex-col max-h-[90vh]" @click.away="showProgramModal = false">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 class="font-black text-xl text-[#444050] uppercase tracking-tight">Programme de la répétition</h3>
                    <p class="text-xs text-slate-500 font-medium">Sélectionnez les chants qui seront travaillés lors de cette séance.</p>
                </div>
                <button @click="showProgramModal = false" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('admin.suivi.repetitions.sync_chants', $repetition) }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-8 overflow-y-auto flex-1 custom-scrollbar-slim">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($allChants as $chant)
                        <label class="relative flex items-center gap-4 p-4 rounded-2xl border-2 transition-all cursor-pointer group"
                               :class="selectedChants.includes({{ $chant->id }}) ? 'border-[#7367F0] bg-[#7367F0]/5' : 'border-slate-50 bg-white hover:border-slate-200'">
                            
                            <input type="checkbox" name="chants[]" value="{{ $chant->id }}" 
                                   x-model="selectedChants" class="hidden">
                            
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-all group-hover:scale-110"
                                 :class="selectedChants.includes({{ $chant->id }}) ? 'bg-[#7367F0] text-white shadow-material' : 'bg-slate-100 text-slate-400'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-[#444050] truncate" :class="selectedChants.includes({{ $chant->id }}) ? 'text-[#7367F0]' : ''">
                                    {{ $chant->title }}
                                </p>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-tighter truncate">
                                    {{ $chant->composer ?: 'Chef de Choeur' }}
                                </p>
                            </div>

                            <div x-show="selectedChants.includes({{ $chant->id }})" class="text-[#7367F0]">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="p-8 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <span x-text="selectedChants.length"></span> chant(s) sélectionné(s)
                    </p>
                    <div class="flex gap-3">
                        <button type="button" @click="showProgramModal = false" class="btn-secondary px-6">Annuler</button>
                        <button type="submit" class="btn-primary px-10">Enregistrer le programme</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Motif Modal -->
    <div x-show="showMotifModal" 
         class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-cloak x-transition.opacity>
        
        <div class="bg-white rounded-3xl w-full max-w-md shadow-material-lg overflow-hidden flex flex-col" @click.away="showMotifModal = false">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-black text-lg text-[#444050] uppercase tracking-tight">Motif de l'absence</h3>
                <button @click="showMotifModal = false" class="text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <p class="text-sm text-slate-500 font-medium">Veuillez indiquer la raison du changement de statut pour ce membre.</p>
                <textarea x-model="currentMotif" 
                          class="w-full h-32 p-4 rounded-2xl bg-slate-50 border-2 border-slate-100 focus:border-[#7367F0] focus:bg-white outline-none transition-all text-sm font-medium"
                          placeholder="Ex: Maladie, Voyage, Travail..."></textarea>
            </div>

            <div class="p-6 bg-slate-50 border-t border-slate-100 flex gap-3">
                <button @click="showMotifModal = false" class="btn-secondary flex-1">Annuler</button>
                <button @click="confirmPresence(currentUserId, currentStatus, currentMotif); showMotifModal = false" class="btn-primary flex-1">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
@endsection
