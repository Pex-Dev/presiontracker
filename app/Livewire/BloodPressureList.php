<?php

namespace App\Livewire;

use App\Models\BloodPressure;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Pagination\LengthAwarePaginator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class BloodPressureList extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $startDate = null;
    public $endDate = null;
    public $registerToDelete = null;

    //Listener utilizado para confirmar la eliminación de un registro
    protected $listeners = [
        'confirmDelete',
    ];

    public function render()
    {
        //Obtener historial de presión arterial
        $pressureHistory = $this->getPressureHistory();

        //Cargar gráfico
        $this->dispatch('loadChart', [
            'labels' => $pressureHistory['registrosChart']->pluck('measured_at')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d/m/Y')),
            'systolic' => $pressureHistory['registrosChart']->pluck('systolic'),
            'diastolic' => $pressureHistory['registrosChart']->pluck('diastolic')
        ]);

        return view('livewire.blood-pressure-list', [
            'registros' => $pressureHistory['registrosAgrupados'],
            'stats' => $pressureHistory['stats']
        ]);
    }

    public function getPressureHistory()
    {

        //Construir consulta
        $query = BloodPressure::where('user_id', auth()->id());

        if (!empty($this->startDate) && !empty($this->endDate)) {
            $start = Carbon::parse($this->startDate)->startOfDay();
            $end = Carbon::parse($this->endDate)->endOfDay();

            $query->whereBetween('measured_at', [$start, $end]);
        }

        //Obtener registros 
        $registros = $query->orderBy('measured_at', 'desc')->get();

        //Agrupar registros por el día
        $registrosAgrupados = $registros->groupBy(function ($registro) {
            return Carbon::parse($registro->measured_at)->format('d-m-Y');
        });

        // Paginar los grupos
        $page = $this->getPage();
        $perPage = 31; // Días por página
        $paginados = $registrosAgrupados->forPage($page, $perPage);

        // Crear un objeto LengthAwarePaginator
        $paginator = new LengthAwarePaginator(
            $paginados, // Los grupos de registros por fecha
            $registrosAgrupados->count(), // El número total de días
            $perPage, // Registros por página
            $page, // La página actual
            ['path' => url()->current()] // Mantener la URL actual
        );

        // Obtener el valor más alto de 'systolic' por cada grupo (día). Esto será utilizado para mostrarse en el grafico
        $resultadosMaximos = $paginados->map(function ($grupo) {
            // Obtenemos el registro con el valor máximo de 'systolic' dentro de cada grupo
            $maxSystolic = $grupo->max('systolic'); // 'systolic' es el campo que buscas
            // Devolver el grupo con solo el valor máximo de 'systolic'
            return $grupo->where('systolic', $maxSystolic)->first();
        });

        //Estadísticas básicas        
        $stats = [
            'avg_systolic' => round($registros->avg('systolic'), 1),
            'avg_diastolic' => round($registros->avg('diastolic'), 1),
            'avg_pulse' => round($registros->avg('pulse'), 1),
        ];

        return [
            'registros' => $registros, //Registros sin agrupar. Usado para generar el PDF
            'registrosAgrupados' => $paginator, //Registros agrupados y paginados para mostrar en la vista
            'registrosChart' => $resultadosMaximos, //Registros para mostrar en el gráfico
            'stats' => $stats //Estadísticas generales
        ];
    }

    public function filterByDate()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->startDate =  null;
        $this->endDate = null;
        $this->resetPage();
    }

    public function deleteRegister($id)
    {
        //Buscar registro
        $bloodPressure = BloodPressure::find($id);

        if ($bloodPressure) {
            //Establecer el registro a eliminar
            $this->registerToDelete = $bloodPressure;

            //Mostrar ventana modal para consultar al usuario si desea eliminar
            $this->alert('question', '¿Eliminar registro?', [
                'showConfirmButton' => true,
                'confirmButtonText' => 'Eliminar',
                'onConfirmed' => 'confirmDelete',
                'showCancelButton' => true,
                'cancelButtonText' => 'Cancelar',
                'position' =>  'center',
            ]);
        }
    }

    public function confirmDelete()
    {
        if ($this->registerToDelete) {
            //Eliminar registro
            $this->registerToDelete->delete();
            $this->registerToDelete = null;
            $this->alert('success', 'Registro eliminado', [
                'position' =>  'center',
            ]);
        }
    }

    public function generateReportPDF()
    {
        // Crear una instancia de Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);


        // Obtener el historial de presión arterial
        $historial = $this->getPressureHistory();

        $periodo = \Carbon\Carbon::parse($historial['registros']->last()->measured_at)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($historial['registros']->first()->measured_at)->format('d/m/Y');

        // El HTML que quieres convertir en PDF
        $html = '
        <html>
            <head>
                <style>
                    table {
                        width: 100%;
                        border-collapse: collapse;                  
                    }
                    th, td {
                        border: 1px solid black;
                        padding: 8px;
                        vertical-align: top;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    div{
                        background-color: #f2f2f2;
                        padding: 10px;
                        border-radius: 8px;
                        margin-bottom: 30px;
                    }
                    p{
                        margin: 0;
                    }
                    .date{
                        min-width: 120px;
                    }
                </style>
                <title>Historial de presión sanguinea</title>
            </head>
            <body>
                <h1>Historial de presión arterial de ' . auth()->user()->name . '</h1>
                <div>
                    <h3 class="font-semibold">Estadísticas Generales</h3>
                    <p>Periodo: ' . $periodo . '</p>
                    <p>Presión Sistólica Promedio:' . $historial['stats']['avg_systolic'] . '</p>
                    <p>Presión Diastólica Promedio:' . $historial['stats']['avg_diastolic'] . '</strong></p>
                    <p>Pulso Promedio:' . $historial['stats']['avg_pulse'] . '</strong></p> 
                </div>
                <table>
                    <thead>
                        <th class="date">Fecha</th>
                        <th>Sistólica</th>
                        <th>Diastólica</th>
                        <th>Pulso</th>
                        <th>Temperatura</th>
                        <th>Nota</th>
                    </thead>
                <tbody>';


        foreach ($historial['registros'] as $registro) {
            $html .= '
                    <tr>
                        <td>' . \Carbon\Carbon::parse($registro->measured_at)->format('d/m/Y h:m') . '</td>
                        <td>' . $registro->systolic . '</td>
                        <td>' . $registro->diastolic . '</td>
                        <td>' . $registro->pulse  . '</td>
                        <td>' . $registro->temperature . '</td>
                        <td>' . $registro->notes . '</td>
                    </tr>
                ';
        }

        $html .= '</tbody>
                </table>
            </body>
        </html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Guardar el PDF en un archivo temporal
        $output = $dompdf->output();
        $pdfPath = storage_path('app/public/historial_presion_' . str_replace('/', '-', $periodo)  . '.pdf');

        file_put_contents($pdfPath, $output);

        // Redirigir al usuario para que descargue el archivo generado y eliminarlo después de enviarlo
        return response()->download($pdfPath)->deleteFileAfterSend(true);
    }
}
