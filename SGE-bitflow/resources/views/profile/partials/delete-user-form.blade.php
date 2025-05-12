<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-black">
            <h2>{{ __('Eliminar cuenta') }}</h2>
        </h2>

        <p class="mt-1 text-sm text-black">
            <h6>{{ __('Una vez eliminada tu cuenta toda la información sera eliminada permanentemente.') }}</h6>
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>
    
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-black ">
                {{ __('Estas seguro de querer eliminar tu cuenta?') }}
            </h2>

            <p class="mt-1 text-sm text-black">
                {{ __('Una vez eliminada tu cuenta toda la informacion sera eliminada permanentemente, si deseas continuar ingresa tu contraseña para confirmar la eliminacion permanente de tu cuenta.') }}
            </p>
            

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <x-custom-text-input 
                    id="password" 
                    name="password" 
                    type="password" 
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Contraseña') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Borrar cuenta') }}
                </x-danger-button>
            </div>
        </form>
        
    </x-modal>
    
</section>
