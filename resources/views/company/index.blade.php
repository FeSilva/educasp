@extends('layouts.app', [
'class' => '',
'elementActive' => 'company'
])

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Paramêtros</a></li>
        <li class="breadcrumb-item active" aria-current="page">Company</li>
    </ol>
</nav>
<div class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Company</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('company.create') }}" class="btn btn-sm btn-primary">Cadastrar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table pi" id="table">
                        <thead class=" text-primary">
                            <th>
                                Razão Social
                            </th>
                            <th>
                                Fantasia
                            </th>
                            <th>
                                CNPJ
                            </th>
                            <th>
                                Ações
                            </th>
                        </thead>
                        <tbody>
                            @foreach($companys as $company)
                                <tr>
                                    <td>{{ $company->razao_social }}</td>
                                    <td>{{ $company->fantasia }}</td>
                                    <td>{{ $company->cnpj }}</td>
                                    <td>
                                        <a href="#">
                                            <img src="{{asset("paper")}}/img/icons/edit.png"  onclick="window.location='{{ route('company.edit', ['company_id' => $company->company_id]) }}'"  width="30px">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection