<section>

    <form id="actualizar_contraseña" method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
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
            <x-custom-button >{{ __('Guardar') }}</x-custom-button>

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
