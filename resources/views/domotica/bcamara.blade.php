<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Graficos') }}
        </h2>
    </x-slot>
    {{-- Contendio del Index --}}
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 bg-white border-b border-gray-200">
                    <div>
                        <p>Hola {{ Auth::user()->name }}</p><br>
                        <p>Por favor, selecciona una cámara y fecha para generar el grafico</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 bg-white border-b border-gray-200">
                    <div>
                        <div class="container py-4">
                    
                            <form action="/bcamara" method="POST">
                                {{ csrf_field() }}
                                <div class=" row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="fecha_actual" class="text-gray-700">Fecha de Búsqueda</label>
                                            <input id="fecha_actual" name="fecha_actual" type="date"
                                                value="<?= $fecha ? $fecha : date('Y-m-d') ?>" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label for="idcamara" class="text-gray-700">Nro Camara</label>
                                            <select name="kmara" id="kmara" class="form-control">
                                                <option value="">Todas las Camara</option>
                                                @foreach ($kmara as $item)
                                                    @if ($item->id_cameraIndexCode == $kmaraSelect)
                                                        <option value="{{ $item->id_cameraIndexCode }}" selected>
                                                            {{ $item->id_cameraIndexCode }}</option>
                                                    @else
                                                        <option value="{{ $item->id_cameraIndexCode }}">{{ $item->id_cameraIndexCode }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="container py-2">
                                        <button class="btn btn-primary">Buscar</button>
                                    </div>
                                </div>
                            </form>
                    
                        </div>
                        <div id="chart_div" style="max-width:auto; height:400px"></div>
                    
                        <script>
                            google.charts.load('current', {
                                packages: ['corechart', 'bar']
                            });
                    
                            google.charts.setOnLoadCallback(drawAxisTickColors);
                    
                            function drawAxisTickColors() {
                                let data = new google.visualization.DataTable();
                                data.addColumn('timeofday', 'Time of Day');
                                data.addColumn('number', 'Entradas');
                                data.addColumn('number', 'Salidas');
                    
                                data.addRows([
                    
                                    <?php foreach ($datosCompletos as $value) : ?>[{
                                        v: [<?= $value['hora_entrada'] ?>, 0, 0],
                                        f: '<?= $value['hora_entrada'] . ':00' ?>'
                                    }, <?= $value['cantidad_entrada'] ?>, <?= $value['cantidad_salida'] ?>],
                                    <?php endforeach; ?>
                                ]);
                    
                                let options = {
                                    title: 'Gráfico estadistico',
                                    focusTarget: 'category',
                                    hAxis: {
                                        title: 'Aforo de Vehiculos',
                                        format: 'h:mm a',
                                        viewWindow: {
                                            min: [7, 30, 0],
                                            max: [22, 30, 0]
                                        },
                                        textStyle: {
                                            fontSize: 14,
                                            color: '#053061',
                                            bold: true,
                                            italic: false
                                        },
                                        titleTextStyle: {
                                            fontSize: 18,
                                            color: '#053061',
                                            bold: true,
                                            italic: false
                                        }
                                    },
                                    vAxis: {
                                        title: 'Cantidad de Vehiculos',
                                        textStyle: {
                                            fontSize: 18,
                                            color: '#67001f',
                                            bold: false,
                                            italic: false
                                        },
                                        titleTextStyle: {
                                            fontSize: 18,
                                            color: '#67001f',
                                            bold: true,
                                            italic: false
                                        }
                                    }
                                };
                    
                                let chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
                                chart.draw(data, options);
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
