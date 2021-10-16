@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard'
])

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        </ol>
    </nav>
    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                {{-- <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-globe text-warning"></i>
                                </div> --}}
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">
                                        <small>PIS (Processo de Intervenção)</small>
                                    </p>

                                    <p class="card-title">{{$return['pis']}}
                                    <p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  <div class="card-footer ">
                          <hr>
                          <div class="stats">
                              <i class="fa fa-refresh"></i> Atualizar
                          </div>
                      </div>-->
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                {{-- <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-money-coins text-success"></i>
                                </div> --}}
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">
                                        <small>Vistorias não Enviadas</small>
                                    </p>


                                    <p class="card-title">{{$return['naoEnviados_LO']}}
                                    <p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-refresh"></i> Atualizar
                        </div>
                    </div>-->
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                {{-- <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-vector text-danger"></i>
                                </div> --}}
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">
                                        <small>Vistorias Aguardando Retorno</small>
                                    </p>
                                    <p class="card-title">{{$return['emAprovacao_LO']}}
                                    <p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-refresh"></i> Atualizar
                        </div>
                    </div>-->
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            {{-- <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-favourite-28 text-primary"></i>
                                </div>
                            </div> --}}
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">
                                        <small>Orçamentos Aguardando Retorno</small>
                                    </p>
                                    <p class="card-title">{{$return['orcamentoNaoAprovado']}}
                                    <p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="card-footer ">
               <hr>
               <div class="stats">
                   <i class="fa fa-refresh"></i> Atualizar
               </div>
           </div>-->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Vistorias Enviadas (LO - Obras)</h5>
                        <p class="card-category">Ultimos 12 meses</p>
                    </div>
                    <div class="card-body ">
                        <canvas id=chartHours width="400" height="100"></canvas>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Estatistica de Vistorias Multiplas</h5>
                    </div>
                    <div class="card-body ">
                        <canvas id="chartEmail"></canvas>
                    </div>
                    <!--<div class="card-footer ">
                        <div class="legend">
                            <i class="fa fa-circle text-primary"></i> Não Enviada
                            <i class="fa fa-circle text-warning"></i> Em Aprovacao
                            <i class="fa fa-circle text-danger"></i> Aprovada
                            <i class="fa fa-circle text-gray"></i> Enviada
                        </div>
                        <hr>

                    </div>-->
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-chart">
                    <div class="card-header">
                        <h5 class="card-title">Vistorias Multiplas (Select type)</h5>

                    </div>
                    <div class="card-body">
                        <canvas id="speedChart" width="400" height="100"></canvas>
                    </div>
                    <!--<div class="card-footer">
                        <div class="chart-legend">
                            <i class="fa fa-circle text-info"></i> Vistorias LO
                            <i class="fa fa-circle text-warning"></i> Vistorias Multiplas
                        </div>
                        <hr/>
                    </div>--->
                </div>
            </div>
        </div>

        <div class="row">
            <!--geral-->
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table total-vistorias-types" id="total-vistorias-types">
                            <thead class=" text-primary">
                            <tr>
                                <th>
                                    Tipo
                                </th>
                                <th>
                                    Total
                                </th>
                                <th>
                                    Não Enviadas
                                </th>
                                <th>
                                    Não Retornadas
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <!-- por usuáriio-->
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table " id="">
                            <thead class=" text-primary">
                            <tr>
                                <th>
                                    Tipo
                                </th>
                                <th>
                                    Total
                                </th>
                                <th>
                                    Não Enviadas
                                </th>
                                <th>
                                    Não Retornadas
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $('.total-vistorias-types').DataTable({
            processing: true,
            serverSide: true,
            language: {
                "lengthMenu": "registros _MENU_ por página",
                "zeroRecords": "Nenhum registro encontrado",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(Total de  _MAX_ total records)",
                "search": "Pesquisar:",
                "paginate": {
                    "first": "Primeiro",
                    "last": "Last",
                    "next": "Próximo",
                    "previous": "Anterior"
                },
            },
            columns: [
                {
                    data: 'tipo_vistoria',
                    name: 'tipo_vistoria',
                },
                {
                    data: 'total_vistorias',
                    name: 'total_vistorias',
                },
                {
                    data: 'nao_enviadas',
                    name: 'nao_enviadas',
                },
                {
                    data: 'nao_retornadas',
                    name: 'nao_retornadas',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
        $(document).ready(function () {


            //Chart vistorias aprovadas e não aprovadas
            $.getJSON('/dashboardCharts', function (data) {
                var jsonChartAprovadasMes = [];
                var jsonChartAprovadasCount = [];
                var jsonChartCountNaoEnviadas = [];
                $.each(data.aprovadas, function (index, value) {
                    jsonChartAprovadasMes.push(value.mes);
                    jsonChartAprovadasCount.push(value.count);
                });
                $.each(data.naoenviadas, function (index, value) {
                    jsonChartCountNaoEnviadas.push(value.count);
                });
                chartsJsSendNotApproved(jsonChartAprovadasMes, jsonChartAprovadasCount, jsonChartCountNaoEnviadas);
            });

            //Chart aprovadas multiplas
            $.getJSON('/dashboardChartsMult', function (data) {
                var jsonChartMultSend = 0;
                var jsonChartMultCreate = 0;
                var jsonChartMultPendSend = 0;
                var jsonChartMultApproved = 0;
                var status = [];
                $.each(data, function (index, value) {
                    status.push(index);
                    if (index == 'Enviado')
                        $.each(value, function (index, valueMult) {
                            jsonChartMultSend += valueMult.count;
                        });
                    if (index == 'cadastro')
                        $.each(value, function (index, valueMult) {
                            jsonChartMultCreate += valueMult.count;
                        });
                    if (index == 'em aprovacao')
                        $.each(value, function (index, valueMult) {
                            jsonChartMultPendSend += valueMult.count;
                        });
                    if (index == 'Aprovado')
                        $.each(value, function (index, valueMult) {
                            jsonChartMultApproved += valueMult.count;
                        });
                });
                chartsJsSendMult(status, jsonChartMultSend, jsonChartMultCreate, jsonChartMultPendSend, jsonChartMultApproved)
            });

            //Chart aprovadas multiplas
            $.getJSON('/dashboardChartsMultType', function (dataType) {


                var jsonChartMultSendType = 0;
                var jsonChartMultCreateType = 0;
                var jsonChartMultPendSendType = 0;
                var jsonChartMultApprovedType = 0;
                var status = [];
                /*$.each(dataType, function (index, value) {
                    if(index == 'Enviado')
                        $.each(value, function (index,valueMult) {
                            jsonChartMultSendType += valueMult.count;
                        });
                    if(index == 'cadastro')
                        $.each(value, function (index,valueMult) {
                            jsonChartMultCreateType += valueMult.count;
                        });
                    if(index == 'em aprovacao')
                        $.each(value, function (index,valueMult){
                            jsonChartMultPendSendType += valueMult.count;
                        });
                    if(index == 'Aprovado')
                        $.each(value, function (index,valueMult) {
                            jsonChartMultApprovedType += valueMult.count;
                        });

                });*/
                chartJsMultType()
            });
        });

        function chartsJsSendNotApproved(jsonChartAprovadasMes, jsonChartAprovadasCount, jsonChartCountNaoEnviadas) {

            chartColor = "#FFFFFF";

            ctx = document.getElementById('chartHours').getContext("2d");
            values = document.getElementById('jsonCharts');
            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: jsonChartAprovadasMes,
                    datasets: [{
                        borderColor: "#f17e5d",
                        label: 'Não Aprovadas',
                        backgroundColor: "#f17e5d",
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        borderWidth: 3,
                        data: jsonChartCountNaoEnviadas
                    },
                        {
                            borderColor: "#6bd098",

                            label: 'Vistorias Aprovadas',
                            backgroundColor: "#6bd098",
                            pointRadius: 0,
                            pointHoverRadius: 0,
                            borderWidth: 3,
                            data: jsonChartAprovadasCount
                        }
                    ]
                },
                options: {
                    legend: {
                        display: true
                    },

                    tooltips: {
                        enabled: true
                    },

                    scales: {
                        yAxes: [{

                            ticks: {
                                fontColor: "#9f9f9f",
                                beginAtZero: false,
                                maxTicksLimit: 5,
                                //padding: 20
                            },
                            gridLines: {
                                drawBorder: false,
                                zeroLineColor: "#ccc",
                                color: 'rgba(255,255,255,0.05)'
                            }

                        }],

                        xAxes: [{
                            barPercentage: 1.6,
                            gridLines: {
                                drawBorder: false,
                                color: 'rgba(255,255,255,0.1)',
                                zeroLineColor: "transparent",
                                display: false,
                            },
                            ticks: {
                                padding: 20,
                                fontColor: "#9f9f9f"
                            }
                        }]
                    },
                }
            });
        }

        function chartsJsSendMult(status, jsonChartMultSend, jsonChartMultCreate, jsonChartMultPendSend, jsonChartMultApproved) {
            ctx = document.getElementById('chartEmail').getContext("2d");

            myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: status,
                    datasets: [{
                        label: "Vistorias Multiplas",
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        backgroundColor: [
                            '#e3e3e3',
                            '#4acccd',
                            '#fcc468',
                            '#ef8157'
                        ],
                        borderWidth: 0,
                        data: [jsonChartMultSend, jsonChartMultCreate, jsonChartMultApproved, jsonChartMultPendSend]
                    }]
                },

                options: {

                    legend: {
                        display: true
                    },

                    pieceLabel: {
                        render: 'percentage',
                        fontColor: ['white'],
                        precision: 2
                    },

                    tooltips: {
                        enabled: true
                    },

                    scales: {
                        yAxes: [{

                            ticks: {
                                display: false
                            },
                            gridLines: {
                                drawBorder: false,
                                zeroLineColor: "transparent",
                                color: 'rgba(255,255,255,0.05)'
                            }

                        }],

                        xAxes: [{
                            barPercentage: 1.6,
                            gridLines: {
                                drawBorder: false,
                                color: 'rgba(255,255,255,0.1)',
                                zeroLineColor: "transparent"
                            },
                            ticks: {
                                display: false,
                            }
                        }]
                    },
                }
            });
        }

        function chartJsMultType() {
            var speedCanvas = document.getElementById("speedChart");

            var dataFirst = {
                data: [0, 19, 15, 20, 30, 40, 40, 50, 25, 30, 50, 70],
                fill: false,
                borderColor: '#fbc658',
                backgroundColor: 'transparent',
                pointBorderColor: '#fbc658',
                pointRadius: 4,
                pointHoverRadius: 4,
                pointBorderWidth: 8,
            };

            var dataSecond = {
                data: [0, 5, 10, 12, 20, 27, 30, 34, 42, 45, 55, 63],
                fill: false,
                borderColor: '#51CACF',
                backgroundColor: 'transparent',
                pointBorderColor: '#51CACF',
                pointRadius: 4,
                pointHoverRadius: 4,
                pointBorderWidth: 8
            };

            var speedData = {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [dataFirst, dataSecond]
            };

            var chartOptions = {
                legend: {
                    display: true,
                    position: 'top'
                }
            };

            var lineChart = new Chart(speedCanvas, {
                type: 'line',
                hover: false,
                data: speedData,
                options: chartOptions
            });
        }

    </script>
@endpush
