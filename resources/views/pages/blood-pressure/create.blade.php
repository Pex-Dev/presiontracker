<x-app-layout>
    <h2 class="bg-gradient-to-r  from-blue-700 to-blue-500 uppercase font-semibold p-1 md:px-2 md:py-1 text-white text-xl md:text-2xl text-center md:text-left"> {{$title}} </h2>
    @if (session('error'))
        <div class="mt-4 p-3">
            <x-message :type="__('error')" :text="__(session('error'))" />
        </div>        
    @endif   
    
    <div class=" p-2 md:p-4">
        <form action="{{ route('blood-pressure.store') }}" method="POST" class="mt-3">
            @csrf
            <div class="bg-blue-100 rounded p-2 mb-3">
                <h3 class="text-gray-700 font-medium text-lg mb-2">Campos Obligatorios</h3>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium" for="measured_at">Fecha y Hora</label>
                    <input type="datetime-local" name="measured_at" required id="measured_at"
                        class="w-full border bg-white border-gray-400 outline-none focus:border-blue-500  rounded p-2" value="{{ now()->format('Y-m-d\TH:i') }}">
                    @error('measured_at')
                    <p class="bg-red-400 text-white font-medium p-2 rounded mt-1"> {{str_replace('now','la fecha actual',str_replace('measured at','fecha y hora',$message))}} </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:gap-3 md:grid-cols-2">
                    <div>
                        <label class="block text-gray-700 font-medium" for="systolic">Presión Sistólica (mmHg)</label>
                        <input type="number" name="systolic" min="50" max="250" id="systolic" required value="{{old('systolic')}}"
                            class="w-full border bg-white border-gray-400 outline-none focus:border-blue-500  rounded p-2" placeholder="Ej: 120">
                        @error('systolic')
                        <p class="bg-red-400 text-white font-medium p-2 rounded mt-1"> {{$message}} </p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium" for="diastolic">Presión Diastólica (mmHg)</label>
                        <input type="number" name="diastolic" min="30" max="150" id="diastolic" required
                            class="w-full border bg-white border-gray-400 outline-none focus:border-blue-500  rounded p-2" placeholder="Ej: 80">
                        @error('diastolic')
                        <p class="bg-red-400 text-white font-medium p-2 rounded mt-1"> {{$message}} </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:gap-3 md:grid-cols-2">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium" for="pulse">Pulso (latidos por minuto)</label>
                    <input type="number" name="pulse" min="30" max="200" id="pulse"
                        class="w-full border border-gray-400 outline-none focus:border-blue-500  rounded p-2" placeholder="Ej: 75">
                    @error('pulse')
                    <p class="bg-red-400 text-white font-medium p-2 rounded mt-1"> {{$message}} </p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium" for="temperature">Temperatura (°C)</label>
                    <input type="number" name="temperature" min="35" max="42" step="0.1" id="temperature"
                        class="w-full border border-gray-400 outline-none focus:border-blue-500  rounded p-2" placeholder="Ej: 36.5">
                    @error('temperature')
                    <p class="bg-red-400 text-white font-medium p-2 rounded mt-1"> {{$message}} </p>
                    @enderror
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium" for="notes">Notas</label>
                <textarea name="notes" id="notes" class="w-full border border-gray-400 outline-none focus:border-blue-500  rounded p-2" rows="3"
                    placeholder="Ej: Me sentí mareado antes de la medición..."></textarea>
                @error('notes')
                <p class="bg-red-400 text-white font-medium p-2 rounded mt-1"> {{$message}} </p>
                @enderror
            </div>
            <div class="flex justify-between md:justify-start gap-3">
                <input type="reset" value="Limpiar"
                    class="border border-blue-500 hover:border-blue-600 hover:text-blue-600 transition-colors  text-blue-500 font-medium p-2 rounded cursor-pointer">
                <input type="submit" value="Confirmar"
                    class="bg-blue-500 hover:bg-blue-600 transition-colors  p-2 text-white font-medium rounded cursor-pointer">

            </div>
        </form>
    </div>
</x-app-layout>