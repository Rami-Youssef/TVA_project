@extends('layouts.app', ['page' => __('Suivi des Déclarations CNSS'), 'pageSlug' => 'suivi'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Déclarations CNSS pour {{ $entreprise->nom }}</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('suivi.index') }}" class="btn btn-sm btn-primary">
                                {{ __('Retour à la liste') }}
                            </a>
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                <a href="{{ route('cnss.create', ['entreprise_id' => $entreprise->id]) }}" class="btn btn-sm btn-success">
                                    {{ __('Ajouter une déclaration') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    <div class="table-responsive">
                        <table class="table tablesorter" id="">
                            <thead class="text-primary">
                                <tr>
                                    <th>Mois</th>
                                    <th>Année</th>
                                    <th>Nombre de Salariés</th>
                                    <th>État</th>
                                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                        <th class="text-center">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($declarations as $declaration)
                                    <tr>
                                        <td>{{ $declaration->french_month }}</td>
                                        <td>{{ $declaration->annee }}</td>
                                        <td>{{ $declaration->Nbr_Salries }}</td>
                                        <td>
                                            <span class="badge badge-{{ $declaration->etat === 'valide' ? 'success' : 'warning' }}">
                                                {{ $declaration->etat === 'valide' ? 'Déclaré' : 'Non déclaré' }}
                                            </span>
                                        </td>
                                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center" style="gap: 0.5rem;">
                                                    @if(Auth::user()->role === 'super_admin')
                                                        <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('cnss.edit', $declaration->id) }}'">
                                                            Edit
                                                        </button>
                                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal-{{ $declaration->id }}">
                                                            Delete
                                                        </button>
                                                    @elseif(Auth::user()->role === 'admin')
                                                        <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('cnss.edit', $declaration->id) }}'">
                                                            Edit
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $declarations->links() }}
                    </div>

                    <!-- ApexCharts Employee Count Visualization -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Évolution du nombre de salariés</h4>
                                </div>
                                <div class="card-body">
                                    <div id="employee-chart" style="height: 350px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Confirmation Modals -->
                    @foreach ($declarations as $declaration)
                        <div class="modal fade" id="confirmDeleteModal-{{ $declaration->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('cnss.delete', $declaration->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content" style="background-color: rgb(82, 95, 127); color: white;">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title" style="color: aliceblue; font-size: 1rem; font-weight: bold;">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" 
                                                    style="filter: invert(1) brightness(0) saturate(100%); cursor: pointer; border: none; background: none;">
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Please enter your password to confirm deletion.</p>
                                            <input type="password" name="password" class="form-control" style="background-color: #4f5e80; color: white;" required autofocus>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- ApexCharts JS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <script>
        $(document).ready(function() {
            // Use the pre-prepared data from the controller that includes all months
            let cnssData = {!! json_encode($chartData) !!};
            
            // Create the ApexCharts instance
            const options = {
                series: [{
                    name: 'Nombre de salariés',
                    data: cnssData
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: {
                        show: true,
                        offsetY: -35, // Move toolbar upward above the chart
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true,
                        },
                        autoSelected: 'zoom'
                    },
                    zoom: {
                        enabled: true
                    },
                    foreColor: '#fff' // Set default text color to white for all chart elements
                },
                dataLabels: {
                    enabled: true,
                    offsetY: -10,
                    style: {
                        fontSize: '12px',
                        colors: ['#fff']
                    },
                    background: {
                        enabled: true,
                        foreColor: '#1e1e2f',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#e14eca',
                        opacity: 0.9,
                    },
                    formatter: function(val) {
                        return val + ' sal';
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    colors: ['#e14eca']
                },
                colors: ['#e14eca'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                        stops: [0, 90, 100],
                        colorStops: [
                            {
                                offset: 0,
                                color: '#e14eca',
                                opacity: 0.7
                            },
                            {
                                offset: 100,
                                color: '#ba54f5',
                                opacity: 0.3
                            }
                        ]
                    }
                },
                xaxis: {
                    type: 'datetime',
                    labels: {
                        style: {
                            colors: '#ffffff' // White text for x-axis labels
                        },
                        formatter: function(val) {
                            const date = new Date(val);
                            const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
                            return months[date.getMonth()] + ' ' + date.getFullYear();
                        }
                    },
                    axisBorder: {
                        show: true,
                        color: '#535353'
                    },
                    axisTicks: {
                        show: true,
                        color: '#535353'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Nombre de salariés',
                        style: {
                            color: '#ffffff' // White text for y-axis title
                        }
                    },
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        style: {
                            colors: ['#ffffff'] // White text for y-axis labels
                        }
                    }
                },
                tooltip: {
                    theme: 'dark', // Dark theme for tooltips for better contrast
                    x: {
                        format: 'MMM yyyy'
                    },
                    y: {
                        formatter: function(val) {
                            return val + ' salariés';
                        }
                    }
                },
                markers: {
                    size: 5,
                    colors: ['#e14eca'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: {
                        size: 7
                    }
                },
                grid: {
                    borderColor: '#535353',
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                    padding: {
                        left: 10,
                        right: 10
                    }
                },
                legend: {
                    labels: {
                        colors: '#ffffff' // White text for legend
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#employee-chart"), options);
            chart.render();
        });
    </script>
@endpush