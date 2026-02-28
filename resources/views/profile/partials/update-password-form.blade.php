<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Changer le Mot de Passe') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Assurez-vous d\'utiliser un mot de passe long et complexe pour rester en sécurité.') }}
        </p>
    </header>

    <form id="update-password-form" class="mt-6 space-y-6">
        @csrf
        {{-- On ne met pas de method/action car c'est géré par JS vers Supabase --}}

        <div>
            <x-input-label for="update_password_password" :value="__('Nouveau Mot de Passe')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full"
                autocomplete="new-password" required />
            <div id="password-error" class="mt-2 text-sm text-red-600 hidden"></div>
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmer le Nouveau Mot de Passe')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full" autocomplete="new-password" required />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button id="password-save-btn">{{ __('Mettre à jour via Supabase') }}</x-primary-button>
            <div id="password-success" class="text-sm text-green-600 font-semibold hidden">
                {{ __('Mot de passe mis à jour !') }}
            </div>
        </div>
    </form>
</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordForm = document.getElementById('update-password-form');
            const passwordInput = document.getElementById('update_password_password');
            const confirmInput = document.getElementById('update_password_password_confirmation');
            const passwordError = document.getElementById('password-error');
            const passwordSuccess = document.getElementById('password-success');
            const passwordBtn = document.getElementById('password-save-btn');

            passwordForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                // Reset UI
                passwordError.classList.add('hidden');
                passwordSuccess.classList.add('hidden');

                const password = passwordInput.value;
                const confirm = confirmInput.value;

                if (password !== confirm) {
                    passwordError.innerText = 'Les mots de passe ne correspondent pas.';
                    passwordError.classList.remove('hidden');
                    return;
                }

                if (password.length < 6) {
                    passwordError.innerText = 'Le mot de passe doit faire au moins 6 caractères.';
                    passwordError.classList.remove('hidden');
                    return;
                }

                passwordBtn.disabled = true;
                const originalText = passwordBtn.innerText;
                passwordBtn.innerText = 'Mise à jour...';

                try {
                    // Initialisation Supabase (déjà faite dans layout ou profile-info-form)
                    const supabaseUrl = "{{ env('SUPABASE_URL') }}";
                    const supabaseKey = "{{ env('SUPABASE_ANON_KEY') }}";
                    const supabaseClient = supabase.createClient(supabaseUrl, supabaseKey);

                    const { data, error } = await supabaseClient.auth.updateUser({
                        password: password
                    });

                    if (error) throw error;

                    // Succès
                    passwordSuccess.classList.remove('hidden');
                    passwordInput.value = '';
                    confirmInput.value = '';

                    // Notification via Toast
                    const toastEvent = new CustomEvent('toast', {
                        detail: { message: 'Votre mot de passe a été mis à jour dans Supabase.', type: 'success' }
                    });
                    window.dispatchEvent(toastEvent);

                    setTimeout(() => {
                        passwordSuccess.classList.add('hidden');
                    }, 3000);

                } catch (error) {
                    console.error('Erreur Supabase Password:', error);
                    passwordError.innerText = 'Erreur Supabase : ' + (error.message || 'Impossible de mettre à jour le mot de passe.');
                    passwordError.classList.remove('hidden');

                    // Notification via Toast pour l'erreur
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { message: 'Erreur Supabase : ' + (error.message || 'Impossible de mettre à jour le mot de passe.'), type: 'error' }
                    }));
                } finally {
                    passwordBtn.disabled = false;
                    passwordBtn.innerText = originalText;
                }
            });
        });
    </script>
@endpush