@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'Documentos OS'
])

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Documentos</a></li>
            <li class="breadcrumb-item active" aria-current="page">OS</li>

        </ol>
    </nav>
    <div class="content">
        <div class="row">
            <div class="col-12">
                <img src="{{ asset('paper/img/Logo_fde.jpg') }}" alt="Logo" class="img-responsive">
            </div>
            <div class="divisor"></div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2 class="text-center">Solicitação de Vistoria Complementar</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p>Sr. Supervisor,</p>
                <p>Solicitamos análise e se de acordo, aprovação de Vistorias Complementares conforme abaixo: </p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td colspan="2" class="thead text-center">Identificação da Obra</td>
                        </tr>
                        <tr>
                            <td class="thead">Nome da Escola</td>
                            <td>EE MARTINS PENA</td>
                        </tr>
                        <tr>
                            <td class="thead">NÚMERO DA OS</td>
                            <td>08/00028/21</td>
                        </tr>
                        <tr>
                            <td class="thead">NÚMERO DA OS GESTÃO SOCIAL</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="thead">PI DA OBRA</td>
                            <td>2020/00978</td>
                        </tr>
                        <tr>
                            <td class="thead">CÓDIGO DA OBRA</td>
                            <td>00.57.112</td>
                        </tr>
                        <tr>
                            <td class="thead">CONTRATO</td>
                            <td>70/00221/20/04-001-001</td>
                        </tr>
                        <tr>
                            <td class="thead">CONTRATADA</td>
                            <td>TROPICO CONSTRUTORA E INCORPORADORA EIRELI</td>
                        </tr>
                        <tr>
                            <td class="thead">CONTRATADA</td>
                            <td>4</td>
                        </tr>
                        <tr>
                            <td class="thead">QUANTIDADES DE VISTORIAS DE SEGURANÇA PREVISTAS NA OS ORIGINAL</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td class="thead">QUANTIDADES DE VISTORIAS DE GESTÃO SOCIAL PREVISTAS NA OS ORIGINAL</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td class="thead">Percentual Executado até a presente data</td>
                            <td>100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
        <div class="row">
            <div class="col-12">
                <h6>JUSTIFICATIVA: </h6>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td class="thead" rowspan="4">QUANTIDADES DE VISTORIAS A COMPLEMENTAR</td>
                        </tr>
                        <tr>
                            <td>Obra</td>
                            <td>4</td>
                        </tr>
                        <tr>
                            <td>Segurança do Trabalho</td>
                            <td>00</td>
                        </tr>
                        <tr>
                            <td>Gestão Social</td>
                            <td>00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="divisor"></div>
        <div class="row">
            <div class="col-12">
                <img src="{{ asset('paper/img/Logo_fde.jpg') }}" alt="Logo" class="img-responsive">
            </div>
            <div class="divisor"></div>
        </div>
        <div class="row">
            <div class="col-12">
                <p class="assinatura">Consórcio</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p class="data">Data: <span class="divisor_data">/</span><span class="divisor_data">/</span></p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <p class="de_acordo">De Acordo, </p>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <p class="assinatura_supervisao">Supervisão de Obras</p>
            </div>
            <div class="col-6">
                <span class="assinatura_gerencia_span">AFFONSO COAN FILHO</span><br>
                <p class="assinatura_gerencia">Gerência de Obras</p>
            </div>
        </div>
    </div>
@endsection