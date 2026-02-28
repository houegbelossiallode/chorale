<div x-data="{ 
    toasts: [],
    addToast(message, type = 'success') {
        const id = Date.now();
        this.toasts.push({ id, message, type });
        setTimeout(() => this.removeToast(id), 5000);
    },
    removeToast(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    }
}" @toast.window="addToast($event.detail.message, $event.detail.type)"
    class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3 items-end pointer-events-none">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
            x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="pointer-events-auto max-w-sm w-full bg-white rounded-xl shadow-material-lg border-l-4 overflow-hidden flex items-center p-4 gap-3 animate-fade-in"
            :class="{
                 'border-[#28C76F]': toast.type === 'success',
                 'border-[#EA5455]': toast.type === 'error',
                 'border-[#FF9F43]': toast.type === 'warning',
                 'border-[#00CFE8]': toast.type === 'info'
             }">

            {{-- Icon --}}
            <div class="shrink-0">
                <template x-if="toast.type === 'success'">
                    <div class="w-8 h-8 rounded-full bg-[#DFF7E9] flex items-center justify-center text-[#28C76F]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </template>
                <template x-if="toast.type === 'error'">
                    <div class="w-8 h-8 rounded-full bg-[#FCEAEA] flex items-center justify-center text-[#EA5455]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </template>
                <template x-if="toast.type === 'warning'">
                    <div class="w-8 h-8 rounded-full bg-[#FFF3E8] flex items-center justify-center text-[#FF9F43]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </template>
                <template x-if="toast.type === 'info'">
                    <div class="w-8 h-8 rounded-full bg-[#E0F9FC] flex items-center justify-center text-[#00CFE8]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </template>
            </div>

            {{-- Message --}}
            <div class="flex-1 min-w-0">
                <p class="text-[14px] font-semibold text-[#444050] truncate" x-text="toast.message"></p>
            </div>

            {{-- Close --}}
            <button @click="removeToast(toast.id)" class="text-slate-300 hover:text-slate-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>

    {{-- Session Flash Messages --}}
    @if(session('success'))
        <div x-init="$nextTick(() => addToast('{{ addslashes(session('success')) }}', 'success'))"></div>
    @endif
    @if(session('error'))
        <div x-init="$nextTick(() => addToast('{{ addslashes(session('error')) }}', 'error'))"></div>
    @endif
    @if(session('warning'))
        <div x-init="$nextTick(() => addToast('{{ addslashes(session('warning')) }}', 'warning'))"></div>
    @endif
    @if(session('info'))
        <div x-init="$nextTick(() => addToast('{{ addslashes(session('info')) }}', 'info'))"></div>
    @endif
</div>