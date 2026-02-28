@extends('layouts.admin')

@section('page_title', 'Nouveau Membre')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.members.index') }}"
                    class="w-9 h-9 bg-white rounded-lg flex items-center justify-center text-slate-400 hover:text-[#7367F0] shadow-sm border border-slate-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h3 class="text-xl sm:text-2xl font-semibold text-[#444050]">Ajouter un Membre</h3>
                    <p class="text-[13px] text-slate-400">Enregistrement d'un nouveau choriste dans l'ERP</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6"
            x-data="{ photoPreview: null }">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Personal info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Information -->
                    <div class="card-material p-6 sm:p-8 space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                            <div class="w-1.5 h-4 bg-[#7367F0] rounded-full"></div>
                            <h4 class="text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Informations
                                Personnelles</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Prénom</label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]"
                                    placeholder="Ex: Jean">
                                @error('first_name') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Nom</label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]"
                                    placeholder="Ex: Dupont">
                                @error('last_name') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Adresse Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]"
                                    placeholder="email@exemple.com">
                                @error('email') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Date de naissance</label>
                                <input type="date" name="date_naissance" value="{{ old('date_naissance') }}"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]">
                                @error('date_naissance') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="p-4 bg-indigo-50/50 rounded-lg border border-indigo-100 flex gap-3">
                            <svg class="w-5 h-5 text-[#7367F0] shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-[12px] text-slate-600 leading-relaxed">
                                <span class="font-bold text-[#7367F0]">Note :</span> Un mot de passe sécurisé sera généré
                                aléatoirement et envoyé par email au nouveau membre.
                            </p>
                        </div>
                    </div>

                    <!-- Role & Section -->
                    <div class="card-material p-6 sm:p-8 space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                            <div class="w-1.5 h-4 bg-[#7367F0] rounded-full"></div>
                            <h4 class="text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Rôle & Section
                            </h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Pupitre</label>
                                <select name="pupitre_id" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px] appearance-none">
                                    <option value="">Sélectionner un pupitre</option>
                                    @foreach($pupitres as $pupitre)
                                        <option value="{{ $pupitre->id }}" {{ old('pupitre_id') == $pupitre->id ? 'selected' : '' }}>{{ $pupitre->name }}</option>
                                    @endforeach
                                </select>
                                @error('pupitre_id') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Rôle au sein de la
                                    chorale</label>
                                <select name="role_id" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px] appearance-none">
                                    <option value="">Sélectionner un rôle</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->libelle}}</option>
                                    @endforeach
                                </select>
                                @error('role_id') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Public Profile Information -->
                    <div class="card-material p-6 sm:p-8 space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                            <div class="w-1.5 h-4 bg-[#FF9F43] rounded-full"></div>
                            <h4 class="text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Profil Public
                                (Trombinoscope)</h4>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Citation Personnelle</label>
                                <input type="text" name="citation" value="{{ old('citation') }}"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]"
                                    placeholder="Une phrase qui vous inspire...">
                                @error('citation') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-1.5">
                                    <label class="text-[12px] font-semibold text-slate-500 ml-1">Activité au sein de la
                                        chorale</label>
                                    <input type="text" name="activite" value="{{ old('activite') }}"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]"
                                        placeholder="Ex: Responsable technique, Soliste...">
                                    @error('activite') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[12px] font-semibold text-slate-500 ml-1">Loisirs / Hobbies</label>
                                    <input type="text" name="hobbie" value="{{ old('hobbie') }}"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]"
                                        placeholder="Ex: Lecture, Voyages, Sport...">
                                    @error('hobbie') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Ce qu'il/elle aime dans la
                                    chorale</label>
                                <textarea name="love_choir" rows="3"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px] resize-none"
                                    placeholder="Partagez votre expérience...">{{ old('love_choir') }}</textarea>
                                @error('love_choir') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Status & Photo -->
                <div class="space-y-6">
                    <!-- Status -->
                    <div class="card-material p-6 sm:p-8">
                        <div class="flex items-center gap-2 pb-4 border-b border-slate-50 mb-6">
                            <div class="w-1.5 h-4 bg-[#28C76F] rounded-full"></div>
                            <h4 class="text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Statut du Compte
                            </h4>
                        </div>

                        <div class="space-y-4">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#28C76F]">
                                    </div>
                                </div>
                                <span
                                    class="text-[14px] font-medium text-slate-600 group-hover:text-[#444050] transition-colors">Activer
                                    immédiatement</span>
                            </label>
                            <p class="text-[11px] text-slate-400">Le membre pourra se connecter dès la création de son
                                compte.</p>
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <div class="card-material p-6 sm:p-8">
                        <div class="flex items-center gap-2 pb-4 border-b border-slate-50 mb-6">
                            <div class="w-1.5 h-4 bg-[#FF9F43] rounded-full"></div>
                            <h4 class="text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Photo de profil
                            </h4>
                        </div>

                        <div class="space-y-6">
                            <!-- Photo Preview -->
                            <div class="flex justify-center">
                                <template x-if="photoPreview">
                                    <div class="relative">
                                        <img :src="photoPreview"
                                            class="w-24 h-24 rounded-xl object-cover border-2 border-[#7367F0]/20 shadow-lg">
                                        <button type="button" @click="photoPreview = null; $refs.photoInput.value = ''"
                                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                                <template x-if="!photoPreview">
                                    <div
                                        class="w-24 h-24 rounded-xl border-2 border-dashed border-slate-200 flex items-center justify-center bg-slate-50">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </template>
                            </div>

                            <!-- Upload Trigger -->
                            <div @click="$refs.photoInput.click()"
                                class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl p-4 bg-slate-50/50 hover:bg-slate-50 hover:border-[#7367F0]/30 transition-all cursor-pointer group">
                                <svg class="w-8 h-8 text-slate-300 mb-2 group-hover:text-[#7367F0]/50 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span
                                    class="text-[11px] font-medium text-slate-500 group-hover:text-[#7367F0] transition-colors">Télécharger
                                    une photo</span>
                                <input type="file" name="photo" x-ref="photoInput" class="hidden" accept="image/*"
                                    @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => photoPreview = e.target.result; reader.readAsDataURL(file); }">
                            </div>
                            <p class="text-[10px] text-center text-slate-400">JPG, PNG ou WebP. Max 2Mo.</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-3 pt-2">
                        <button type="submit"
                            class="btn-primary w-full py-4 text-[14px] flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Créer le membre
                        </button>
                        <a href="{{ route('admin.members.index') }}"
                            class="btn-secondary w-full py-4 text-[14px] text-center">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection