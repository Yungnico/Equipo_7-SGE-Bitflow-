<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            <h2>{{ __('Cambiar contraseña') }}</h2>
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            <h5>{{ __('Asegura tu cuenta usando una contraseña larga y aleatoria para estar seguro.') }}</h5>
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-custom-input-label for="update_password_current_password" :value="__('Contraseña actual')" />
            <x-custom-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-custom-input-label for="update_password_password" :value="__('Nueva contraseña')" />
            <x-custom-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-custom-input-label for="update_password_password_confirmation" :value="__('Confirmar contraseña nueva')" />
            <x-custom-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-custom-button >{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('¡Cambio exitoso!') }}</p> <!-- Mensaje de éxito {{ __('¡Cambio exitoso!') }}-->
            @endif
        </div>
    </form>
</section>


<!-- aqui ver lo del sweet alert -->