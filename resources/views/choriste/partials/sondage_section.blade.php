<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6" x-data="sondageHandler()">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#7367F0]/10 text-[#7367F0] flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <h4 class="text-base font-black text-[#444050]">Sondage de présence</h4>
                <p class="text-xs text-slate-400 font-medium">Confirmez-vous votre présence ?</p>
            </div>
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <button @click="vote('oui')" 
                :class="choice === 'oui' ? 'bg-[#28C76F] text-white shadow-[#28C76F]/20' : 'bg-slate-50 text-slate-400 hover:bg-[#28C76F]/10 hover:text-[#28C76F] border-slate-100'"
                class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all border shadow-sm flex items-center justify-center gap-2">
                <svg x-show="choice === 'oui'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                Oui
            </button>
            <button @click="vote('non')" 
                :class="choice === 'non' ? 'bg-[#EA5455] text-white shadow-[#EA5455]/20' : 'bg-slate-50 text-slate-400 hover:bg-[#EA5455]/10 hover:text-[#EA5455] border-slate-100'"
                class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all border shadow-sm flex items-center justify-center gap-2">
                <svg x-show="choice === 'non'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                Non
            </button>
            <button @click="vote('peut-etre')" 
                :class="choice === 'peut-etre' ? 'bg-[#FF9F43] text-white shadow-[#FF9F43]/20' : 'bg-slate-50 text-slate-400 hover:bg-[#FF9F43]/10 hover:text-[#FF9F43] border-slate-100'"
                class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all border shadow-sm flex items-center justify-center gap-2">
                <svg x-show="choice === 'peut-etre'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                ?
            </button>
        </div>
    </div>

    <script>
        function sondageHandler() {
            return {
                choice: @json($userSondage ? $userSondage->choix : null),
                isVoting: false,
                
                async vote(newChoice) {
                    if (this.isVoting) return;
                    this.isVoting = true;
                    
                    const payload = {
                        choix: newChoice,
                        _token: '{{ csrf_token() }}'
                    };
                    
                    @if(isset($repetition))
                        payload.repetition_id = {{ $repetition->id }};
                    @endif
                    
                    @if(isset($event))
                        payload.event_id = {{ $event->id }};
                    @endif

                    try {
                        const response = await fetch('{{ route("choriste.sondages.vote") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await response.json();
                        if (response.ok) {
                            this.choice = newChoice;
                            // Optionally show a toast or notification
                        } else {
                            alert(data.message || "Erreur lors du vote");
                        }
                    } catch (err) {
                        alert("Erreur réseau");
                    } finally {
                        this.isVoting = false;
                    }
                }
            }
        }
    </script>
</div>
