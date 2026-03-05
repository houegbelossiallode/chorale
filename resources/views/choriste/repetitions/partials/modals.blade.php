<!-- Recording Modal -->
<div x-show="modalOpen"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-cloak>

    <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl" @click.away="closeModal()">
        <div class="p-8 text-center space-y-6">
            <div class="space-y-2">
                <h3 class="text-xl font-bold text-[#444050]" x-text="currentChantTitle"></h3>
                <p class="text-xs text-slate-400 font-medium">Enregistrement pour ce répertoire</p>
            </div>

            <!-- Recorder UI -->
            <div class="flex flex-col items-center justify-center space-y-8 py-4">
                <div
                    class="w-full h-32 bg-slate-50 rounded-2xl flex items-center justify-center relative overflow-hidden">
                    <div x-show="isRecording" class="flex items-center gap-1">
                        <template x-for="i in 12" :key="i">
                            <div class="w-1.5 bg-[#7367F0] rounded-full animate-bounce"
                                :style="{ height: Math.random() * 3 + 1 + 'rem', animationDelay: i * 0.1 + 's' }">
                            </div>
                        </template>
                    </div>
                    <div x-show="!isRecording" class="text-slate-200">
                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z" />
                            <path
                                d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z" />
                        </svg>
                    </div>
                    <div x-show="isRecording" class="absolute top-3 right-4 flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                        <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest"
                            x-text="formatTime(recordingTime)">00:00</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button x-show="!isRecording && !audioBlob" @click="startRecording()"
                        class="w-16 h-16 bg-[#7367F0] rounded-full flex items-center justify-center text-white shadow-lg shadow-[#7367F0]/40 hover:scale-105 transition-all">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z" />
                            <path
                                d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z" />
                        </svg>
                    </button>

                    <button x-show="isRecording" @click="stopRecording()"
                        class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center text-white shadow-lg shadow-red-500/40 hover:scale-105 animate-pulse transition-all">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 6h12v12H6z" />
                        </svg>
                    </button>

                    <div x-show="audioBlob && !isRecording" class="flex items-center gap-4">
                        <button @click="startRecording()"
                            class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 hover:text-[#7367F0] transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                        <button @click="saveRecording()" :disabled="isSaving"
                            class="h-14 px-8 bg-[#28C76F] rounded-2xl text-white font-bold shadow-lg shadow-[#28C76F]/20 flex items-center gap-2 hover:scale-105 transition-all disabled:opacity-50">
                            <span x-show="!isSaving">Déposer ma voix</span>
                            <span x-show="isSaving">Upload...</span>
                        </button>
                    </div>
                </div>
            </div>

            <button @click="closeModal()"
                class="text-slate-400 text-[10px] font-bold hover:text-[#444050] transition-colors uppercase tracking-widest">Fermer</button>
        </div>
    </div>
</div>

<!-- Lyrics Modal -->
<div x-show="lyricsOpen"
    class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-cloak>

    <div class="bg-white rounded-[2rem] w-full max-w-lg overflow-hidden shadow-2xl relative"
        @click.away="lyricsOpen = false">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <span
                        class="text-[10px] font-bold text-[#7367F0] uppercase tracking-widest block mb-1">Paroles</span>
                    <h3 class="text-xl font-bold text-[#444050]" x-text="currentChantTitle"></h3>
                </div>
                <button @click="lyricsOpen = false"
                    class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="bg-slate-50 rounded-2xl p-6 max-h-[50vh] overflow-y-auto custom-scrollbar-slim">
                <div class="text-sm text-slate-600 font-medium leading-relaxed font-sans" x-html="currentLyrics"></div>
            </div>
        </div>
    </div>
</div>