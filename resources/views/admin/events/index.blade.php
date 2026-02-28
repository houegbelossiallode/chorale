@extends('layouts.admin')

@section('page_title', 'Gestion Agenda')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl sm:text-2xl font-semibold text-[#444050]">Agenda de la Chorale</h3>
                <p class="text-[13px] text-slate-400">Planification des répétitions et concerts</p>
            </div>
            <a href="{{ route('admin.events.create') }}" class="btn-primary gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvel Événement
            </a>
        </div>

    </div>

    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
        <div class="bg-white rounded-2xl shadow-material-sm border border-slate-100 hover:border-[#7367F0]/30 transition-all group overflow-hidden flex flex-col">
            <!-- Header Image / Placeholder -->
            <div class="h-32 bg-slate-50 relative overflow-hidden shrink-0">
                @if($event->principalImage)
                    <img src="{{ $event->principalImage->image_path }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                        <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
                
                <!-- Type Badge -->
                <div class="absolute top-4 left-4">
                    <span class="px-3 py-1 bg-white/90 backdrop-blur-md text-[#7367F0] rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm border border-[#7367F0]/10">
                        {{ $event->type->libelle }}
                    </span>
                </div>

                <!-- Date Badge Floating -->
                <div class="absolute top-4 right-4 w-12 h-12 bg-white rounded-xl shadow-lg flex flex-col items-center justify-center border border-slate-50">
                    <span class="text-[9px] font-bold text-[#7367F0] uppercase leading-none">{{ $event->start_at->translatedFormat('M') }}</span>
                    <span class="text-[16px] font-extrabold text-[#444050] leading-none">{{ $event->start_at->format('d') }}</span>
                </div>
            </div>

            <div class="p-6 flex flex-col flex-grow">
                <div class="mb-4">
                    <h3 class="text-[17px] font-bold text-[#444050] line-clamp-1 mb-1 group-hover:text-[#7367F0] transition-colors">{{ $event->title }}</h3>
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-[11px] font-medium">{{ $event->start_at->format('H:i') }} • {{ $event->location }}</span>
                    </div>
                </div>

                <p class="text-[13px] text-slate-500 line-clamp-2 mb-6 min-h-[40px]">
                    {{ $event->description ?: 'Aucune description fournie.' }}
                </p>

                <!-- Actions -->
                <div class="mt-auto pt-5 border-t border-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.events.show', $event) }}" class="p-2 text-slate-400 hover:text-[#7367F0] hover:bg-[#7367F0]/5 rounded-lg transition-all" title="Détails">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('admin.events.edit', $event) }}" class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Modifier">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </a>
                    </div>

                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Supprimer cet événement ?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Supprimer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
            <svg class="w-12 h-12 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="text-sm font-medium">Aucun événement n'a été créé pour le moment.</p>
        </div>
        @endforelse
    </div>

    @if($events->hasPages())
    <div class="mt-8 px-4">
        {{ $events->links() }}
    </div>
    @endif
    </div>
@endsection