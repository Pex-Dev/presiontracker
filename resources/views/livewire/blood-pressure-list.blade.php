<div class="p-2 md:p-4">
    @if (session('success'))
        <x-message :type="__('success')" :text="__(session('success'))" />
    @endif    
    <div class="flex flex-col-reverse md:flex-row md:justify-between">
        {{-- Filtro de fecha --}}
        <form class="flex flex-col mt-7 md:mt-0 md:flex-row gap-2" wire:submit="filterByDate">
            <div class="flex space-x-2 w-full md:w-auto">
                <div class="flex flex-col w-full md:flex-row md:gap-2">
                    <label for="start_date">Desde:</label>
                    <input 
                        id="start_date" 
                        type="date" 
                        class="border rounded p-2 min-w-[130px] w-full" 
                        wire:model="startDate"
                        >
                </div>
                <div class="flex flex-col w-full md:flex-row md:gap-2">
                    <label for="end_date">Hasta:</label>
                    <input 
                        id="end_date" 
                        type="date" 
                        class="border rounded p-2 min-w-[130px] w-full" 
                        wire:model="endDate">
                </div>
            </div>   
            <div class="flex gap-2">
                {{-- Botón de filtrar --}}
                <button 
                    class="flex gap-2 justify-center border px-4 py-2 rounded hover:bg-gray-100"
                    type="submit"
                >
                    <svg 
                        xmlns="http://www.w3.org/2000/svg" 
                        viewBox="0 0 24 24" 
                        fill="none" 
                        stroke="currentColor" 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        width="25" 
                        height="25" 
                        stroke-width="1"
                    >
                        <path d="M4 6h16"></path>
                        <path d="M7 12h13"></path>
                        <path d="M10 18h10"></path>
                    </svg>
                    <span>Filtrar</span>
                </button>
                @if ($startDate || $endDate)
                    <button 
                        class="flex gap-2 justify-center border px-4 py-2 rounded hover:bg-gray-100"
                        wire:click="resetFilters"
                        type="reset"
                    >
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            viewBox="0 0 24 24" 
                            fill="none" 
                            stroke="currentColor" 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            width="25" 
                            height="25" 
                            stroke-width="1"
                        >
                            <path d="M19 7l-7 10l-4 -4l-8 8"></path>
                        </svg>
                        <span>Limpiar</span>
                    </button>
                    
                @endif
            </div>           
        </form>
        {{-- Enlace para crear un nuevo registro --}}
        <a 
            href="{{ route('blood-pressure.create') }}"
            class="flex gap-2 justify-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            <svg 
                xmlns="http://www.w3.org/2000/svg" 
                viewBox="0 0 24 24" 
                fill="none" 
                stroke="currentColor" 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                width="25" 
                height="25" 
                stroke-width="1">
                <path d="M12 5l0 14"></path>
                <path d="M5 12l14 0"></path>
            </svg>
            <span>Añadir Registro</span>
        </a>
    </div>
    {{-- Estadísticas Generales --}}
    @if ($stats['avg_systolic'])
        <div class="flex flex-col md:flex-row justify-between items-start mt-4 mb-4 p-4 bg-gray-100 rounded-lg">
            <div>
                <h3 class="font-semibold">Estadísticas Generales</h3>
                <p>Periodo: <span class="font-semibold">{{ \Carbon\Carbon::parse($registros->first()->first()->measured_at)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($registros->last()->last()->measured_at)->format('d/m/Y') }}</span></p>
                <p>Presión Sistólica Promedio: <strong>{{ $stats['avg_systolic'] ?: 'N/A' }} mmHg</strong></p>
                <p>Presión Diastólica Promedio: <strong>{{ $stats['avg_diastolic'] ?: 'N/A' }} mmHg</strong></p>
                <p>Pulso Promedio: <strong>{{ $stats['avg_pulse'] ?: 'N/A' }} BPM</strong></p> 
            </div>
            {{-- Botón para exportar a PDF --}}
            <button 
                class="flex flex-row-reverse space-x-1 md:space-x-2 mt-3 md:mt-0 items-end bg-white rounded p-1 border border-green-800 text-green-800 cursor-pointer"
                wire:click="generateReportPDF">
                <span>Exportar Historial</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="32" height="32" stroke-width="1">
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                    <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4"></path>
                    <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6"></path>
                    <path d="M17 18h2"></path>
                    <path d="M20 15h-3v6"></path>
                    <path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z"></path>
                </svg>
            </button>
        </div>       
    @endif
    {{-- Lista con los registros --}}
    @if (count($registros)>0)
        <ul class="flex flex-col gap-4 mt-4">
            {{-- Registros por días --}}
            @foreach ($registros as $measured_at => $grupo)
                <li class="bg-gray-50 shadow border border-gray-300 rounded-lg p-2 md:p-3">
                    <header class="flex justify-between">
                        <div class="flex gap-1 items-center">
                            <svg 
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" 
                                fill="none" 
                                stroke="currentColor" 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                width="25" 
                                height="25" 
                                stroke-width="1"> 
                                <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path> 
                                <path d="M16 3v4"></path> 
                                <path d="M8 3v4"></path> 
                                <path d="M4 11h16"></path> 
                                <path d="M11 15h1"></path> 
                                <path d="M12 15v3"></path> 
                            </svg> 
                            <span class="text-gray-700 text-lg" > {{ ucfirst(\Carbon\Carbon::parse($measured_at)->locale('es')->isoFormat('ddd D MMM YYYY')) }} </span>
                        </div>                        
                    </header>
                    <ul class="flex flex-col gap-1">
                        {{-- Registros por horas --}}
                        @foreach ($grupo as $registro)
                            <li class="bg-gray-100 rounded-md p-2 border border-gray-200 flex flex-col mt-1 group">
                                <header class="flex justify-between">
                                    <h3 class="text-gray-600">{{ \Carbon\Carbon::parse($registro->measured_at)->format('h:i a') }} </h3>
                                    <div class="flex md:hidden group-hover:flex gap-2 md:gap-4">
                                        <a 
                                            href="{{ route('blood-pressure.edit', $registro) }}"
                                            class="flex flex-row-reverse items-center justify-end gap-1 cursor-pointer"
                                        >
                                            <span class="hidden md:block">Editar</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" 
                                                    viewBox="0 0 24 24" 
                                                    fill="none" 
                                                    stroke="currentColor" 
                                                    stroke-linecap="round" 
                                                    stroke-linejoin="round" 
                                                    width="25" 
                                                    height="25" 
                                                    stroke-width="1">
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                <path d="M16 5l3 3"></path>
                                            </svg>
                                        </a>
                                        <button 
                                            class="flex flex-row-reverse items-center gap-1  cursor-pointer"
                                            wire:click="deleteRegister({{ $registro->id }})"
                                        >
                                            <span class="hidden md:block">Eliminar</span>
                                            <svg 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                viewBox="0 0 24 24" 
                                                fill="none" 
                                                stroke="currentColor" 
                                                stroke-linecap="round" 
                                                stroke-linejoin="round" 
                                                width="25" 
                                                height="25" 
                                                stroke-width="1">
                                                <path d="M4 7l16 0"></path>
                                                <path d="M10 11l0 6"></path>
                                                <path d="M14 11l0 6"></path>
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </header>
                                <div class="flex gap-4">
                                    <p class="flex flex-col text-gray-600">
                                        Sistólica
                                        <span @class([
                                            'text-blue-500 text-xl font-medium' => $registro->systolic >= 1 && $registro->systolic <= 90,
                                            'text-green-500 text-xl font-medium' => $registro->systolic > 90 && $registro->systolic <= 120,
                                            'text-yellow-500 text-xl font-medium' => $registro->systolic > 120 && $registro->systolic <= 140,
                                            'text-red-500 text-xl font-medium' => $registro->systolic > 140,
                                        ])>{{ $registro->systolic }}</span>
                                    </p>
                                    <p class="flex flex-col text-gray-600">
                                        Diastólica
                                        <span @class([
                                            'text-blue-500 text-xl font-medium' => $registro->diastolic >= 1 && $registro->diastolic <= 60,
                                            'text-green-500 text-xl font-medium' => $registro->diastolic > 60 && $registro->diastolic <= 80,
                                            'text-yellow-500 text-xl font-medium' => $registro->diastolic > 80 && $registro->diastolic <= 90,
                                            'text-red-500 text-xl font-medium' => $registro->diastolic > 90,
                                        ])>{{ $registro->diastolic }}</span>
                                    </p>
                                    <p class="flex flex-col text-gray-600">
                                        Pulso
                                        <span class="text-xl">{{ $registro->pulse ?: 'N/A' }}</span>
                                    </p>
                                    <p class="flex flex-col text-gray-600">
                                        Temperatura
                                        <span class="text-xl">{{ $registro->temperature ?: 'N/A' }}</span>
                                    </p>
                                </div>
                                @if ($registro -> notes)
                                    <div class="md:mt-2 bg-gray-200 p-2 rounded w-full">
                                        <span class="text-gray-600">Nota:</span>
                                        <div class="overflow-auto max-h-[150px]">
                                            <p class="text-gray-800">{{ $registro->notes}}</p>
                                        </div>                                
                                    </div>   
                                @endif
                            </li>                        
                        @endforeach
                    </ul> 
                </li>

                  
                
            @endforeach
                        
        </ul>
        {{-- Grafico de presion --}}
        <div class="mt-6">
            <h3 class="font-semibold mb-2">Gráfico de Presión Arterial</h3>
            <canvas id="pressureChart" class="md:min-h-[559px] border border-gray-200 md:p-1 rounded"></canvas>
        </div>
        {{-- Enlaces para navegar en las paginas con los resultados --}}
        <div class="mt-4">
            {{ $registros->links() }}
        </div>       
    @else
        <p class="text-xl text-center mt-10">No hay registros</p>    
    @endif   
    
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>   
        @vite('resources/js/pressure-chart.js')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <x-livewire-alert::scripts /> 
    @endpush  
</div>  