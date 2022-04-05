<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vehiculos') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div>
                        <p>Bienvenid@ {{ Auth::user()->name }}</p>
                        <p>Acá encontrarás una lista de las cámaras</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div>
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <table id="dataTable" class="table-fixed w-full">
                                <thead>
                                    <tr class="bg-sky-600 text-white">
                                        <th class="w-1/8 py-4">Fecha</th>
                                        <th class="w-1/16 py-4">Orientacion</th>
                                        <th class="w-1/16 py-4">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vehiculos as $listarAuto)
                                        <tr>
                                            <th>{{ date('d/m/Y', strtotime($listarAuto->Fecha)) }}</th>
                                            <th>{{ $listarAuto->Orientacion }}</th>
                                            <th>{{ $listarAuto->Cantidad }}</th>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('#dataTable').DataTable();
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
