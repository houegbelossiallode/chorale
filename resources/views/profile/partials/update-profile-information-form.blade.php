<section>
    @if($user->photo_url)
        <div class="mb-6 flex items-center gap-4 p-4 bg-slate-50 rounded-lg border border-slate-100">
            <div class="shrink-0">
                <img id="current-photo-preview" src="{{ $user->photo_url }}" alt="Photo de profil"
                    class="w-20 h-20 rounded-full object-cover border-2 border-[#7367f0] shadow-sm">
            </div>
            <div>
                <p class="text-[14px] font-semibold text-[#444050]">Photo Actuelle</p>
                <p class="text-[12px] text-slate-400">Cette image est affichée sur votre profil public.</p>
            </div>
        </div>
    @endif

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6" id="profile-update-form"
        enctype="multipart/form-data">
        @csrf
        @method('patch')

        <input type="hidden" name="photo_url" id="photo_url_hidden" value="{{ $user->photo_url }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="first_name" :value="__('Prénom')" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full"
                    :value="old('first_name', $user->first_name)" required autofocus autocomplete="given-name" />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Nom')" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full"
                    :value="old('last_name', $user->last_name)" required autocomplete="family-name" />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <div>
                <x-input-label for="date_naissance" :value="__('Date de naissance')" />
                <x-text-input id="date_naissance" name="date_naissance" type="date" class="mt-1 block w-full"
                    :value="old('date_naissance', $user->date_naissance ? \Carbon\Carbon::parse($user->date_naissance)->format('Y-m-d') : '')" />
                <x-input-error class="mt-2" :messages="$errors->get('date_naissance')" />
            </div>

            <div>
                <x-input-label for="citation" :value="__('Citation')" />
                <x-text-input id="citation" name="citation" type="text" class="mt-1 block w-full"
                    :value="old('citation', $user->citation)" placeholder="Une phrase qui vous inspire..." />
                <x-input-error class="mt-2" :messages="$errors->get('citation')" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="photo" :value="__('Choisir une Image de Profil')" />
                <x-text-input id="photo" name="photo" type="file"
                    class="mt-1 block w-full p-2 border rounded-lg focus:ring-[#7367f0]" accept="image/*" />
                <p class="text-[12px] text-slate-400 mt-1">Formats acceptés : JPG, PNG, GIF. Max 2Mo.</p>
                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
            </div>

            <div>
                <x-input-label for="activite" :value="__('Profession / Activité')" />
                <x-text-input id="activite" name="activite" type="text" class="mt-1 block w-full"
                    :value="old('activite', $user->activite)" placeholder="Votre métier ou activité principale..." />
                <x-input-error class="mt-2" :messages="$errors->get('activite')" />
            </div>

            <div>
                <x-input-label for="hobbie" :value="__('Loisirs / Hobbies')" />
                <x-text-input id="hobbie" name="hobbie" type="text" class="mt-1 block w-full" :value="old('hobbie', $user->hobbie)" placeholder="Ce que vous aimez faire..." />
                <x-input-error class="mt-2" :messages="$errors->get('hobbie')" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="love_choir" :value="__('Ce que j\'aime dans la chorale')" />
                <textarea id="love_choir" name="love_choir" rows="3"
                    class="mt-1 block w-full border-gray-300 focus:border-[#7367f0] focus:ring-[#7367f0] rounded-md shadow-sm"
                    placeholder="Partagez votre expérience et ce qui vous passionne ici...">{{ old('love_choir', $user->love_choir) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('love_choir')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <x-primary-button id="save-profile-btn">{{ __('Enregistrer les modifications') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-semibold">{{ __('Enregistré.') }}</p>
            @endif
        </div>
    </form>
</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('profile-update-form');
            const photoInput = document.getElementById('photo');
            const previewImg = document.getElementById('current-photo-preview');
            const saveBtn = document.getElementById('save-profile-btn');

            // Aperçu de l'image sélectionnée
            photoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        // Créer l'élément de prévisualisation s'il n'existe pas
                        if (!previewImg) {
                            // On pourrait l'injecter dynamiquement, mais il est déjà là si photo_url existe
                            // Ici on se contente de mettre à jour s'il existe
                            const existingPreview = document.getElementById('current-photo-preview');
                            if (existingPreview) existingPreview.src = e.target.result;
                        } else {
                            previewImg.src = e.target.result;
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });

            // On ajoute un indicateur de chargement lors de la soumission habituelle du formulaire
            form.addEventListener('submit', function () {
                saveBtn.disabled = true;
                const originalText = saveBtn.innerText;
                saveBtn.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Envoi en cours...
                        `;
            });
        });
    </script>
@endpush