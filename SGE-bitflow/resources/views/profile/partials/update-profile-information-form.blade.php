<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 ">
            <h2>{{ __('Información del perfil') }}</h2>
        </h2>

        <p class="mt-1 text-sm text-gray-600 ">
            <h6>{{ __("Actualiza tu información del perfil o tu dirección de correo electrónico.") }}</h6>
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form id="actualizar_perfil" method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-custom-input-label for="name" :value="__('Nombre')" />
            <x-custom-text-input 
                id="name" 
                name="name" 
                :value="old('name', $user->name)" 
                required 
                autofocus
                autocomplete="off" 
                type="text"
                class="mt-1 block w-full"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-custom-input-label for="email" :value="__('Email')" />
            <x-custom-text-input 
                id="email" 
                name="email"
                type="email"
                :value="old('email', $user->email)" 
                required 
                autocomplete="username" 
                class="mt-1 block w-full"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            <!--
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
            -->
        </div>

        <div class="flex items-center gap-4">
            <x-custom-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-gray-600"
                >{{ __('') }}</p> <!-- Mensaje de éxito -->
            @endif
        </div>
    </form>
</section>

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('actualizar_perfil').addEventListener('submit', function(e) {
    e.preventDefault(); // Evita el envío inmediato
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Asegurate bien antes de guardar!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Guardado!",
                text: "Se ha actualizado tu perfil correctamente.",
                icon: "success",
                confirmButtonColor: "#3085d6"
            }).then(() => {
                e.target.submit(); // Envía el formulario después de cerrar el SweetAlert de éxito
            });
        }
    });
});
</script>
@endsection
