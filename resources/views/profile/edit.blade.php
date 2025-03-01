<x-app-layout>
    <h2 class="bg-gradient-to-r  from-blue-700 to-blue-500 uppercase font-semibold p-1 md:px-2 md:py-1 text-white text-xl md:text-2xl text-center md:text-left"> Perfil </h2>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-blue-100 rounded p-2">
                @include('profile.partials.update-profile-information-form') 
            </div>
            @if ($user -> name !='Invitado')
                <div class="bg-blue-100 rounded p-2">
                    @include('profile.partials.update-password-form') 
                </div>
            @endif           
            @if ($user -> name !='Invitado')
                <div class="bg-red-100 rounded p-2">
                    @include('profile.partials.delete-user-form')
                </div>    
            @endif                
         </div>
    </div>
</x-app-layout>
