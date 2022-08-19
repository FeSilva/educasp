<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    </head>
    <style type="text/css">
        body{
            font-size: 12px;
        }

        .input-escondido, .input-escondido:focus, .input-escondido:active {
            border: transparent;
            outline: transparent;
            cursor: not-allowed;
            background: #FFF !important;
        }
        
        select.form-control:not([size]):not([multiple]) {
            height: 42px;
        }
        
        .form-group input[type=file] {
            opacity: 1;
            height: 42px;
        }
        
        table .table-borderd {
            border: 1px solid #CCC;
        }
        .divisor {

        }
        
        table tr td {
            text-transform: uppercase;
        }
        
        table .thead {
            font-weight: bold;
        }
        
        .assinatura {
            margin-top: 200px;
            text-transform: uppercase;
            font-weight: bold;
            border-top: 1px solid #444;
            max-width: 300px;
        }
        
        .data, .de_acordo {
            font-weight: bold;
            margin-top: 40px;
        }
        
        .data .divisor_data {
            padding: 0 20px;
        }
        
        .assinatura_supervisao, .assinatura_gerencia {
            margin-top: 100px;
            text-transform: uppercase;
            font-weight: bold;
            border-top: 1px solid #444;
            max-width: 300px;
        }
        
        .assinatura_gerencia_span {
            position: relative;
            top: 80px;
        }
        
        .assinatura_gerencia {
            margin-top: 80px;
        }
    </style>
    <body>
        <div>
            <table class="table">
                <tbody>
                    <tr>
                        <td colspan="2">
                            <img src="{{asset('paper').'/img/Logo_fde.jpg'}}" alt="LogoTeste" class="img-responsive">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h5 class="text-center">Solicitação de Vistoria Complementar</h5>
                        </td>
                    </tr>
                </tbody>
            </table>
             
            <div class="row">
                <div class="col-12">
                    <p>Sr. Supervisor,</p>
                    <p>Solicitamos análise e se de acordo, aprovação de Vistorias Complementares conforme abaixo: </p>
                </div>
            </div>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td colspan="2" class="thead text-center">Identificação da Obra</td>
                    </tr>
                    <tr>
                        <td class="thead">Nome da Escola</td>
                        <td>{{ $document->nome_escola }}</td>
                    </tr>
                    <tr>
                        <td class="thead">NÚMERO DA OS</td>
                        <td>{{ $document->numero_os }}</td>
                    </tr>
                    <tr>
                        <td class="thead">NÚMERO DA OS GESTÃO SOCIAL</td>
                        <td>{{ $document->numero_gestao_social }}</td>
                    </tr>
                    <tr>
                        <td class="thead">PI DA OBRA</td>
                        <td>{{ $document->pi }}</td>
                    </tr>
                    <tr>
                        <td class="thead">CÓDIGO DA OBRA</td>
                        <td>{{ $document->codigo }}</td>
                    </tr>
                    <tr>
                        <td class="thead">CONTRATO</td>
                        <td>{{ $document->contrato}}</td>
                    </tr>
                    <tr>
                        <td class="thead">CONTRATADA</td>
                        <td>{{ $document->nome_contratada }}</td>
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
                        <td>{{ $document->percentual }}%</td>
                    </tr>
                </tbody>
            </table>

            <table class="table">
                <tbody>
                    <tr>
                        <td colspan="2">
                            <h7>JUSTIFICATIVA: {{ $document->justificativa }}</h7>
                        </td>
                    </tr>
                </tbody>
            </table>
			<table class="table table-bordered" border="1">
				<tbody>
					<tr>
						<td class="thead" rowspan="4">QUANTIDADES DE VISTORIAS A COMPLEMENTAR</td>
					</tr>
					<tr>
						<td>OBRAS</td>
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
           
            <div class="row">
                <div class="col-12">
                    <img src="http://www.fde.sp.gov.br/imagens/logo_FDE.png" width="200px" style="margin-top: 20px" alt="Logo" class="img-responsive">
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
                <div class="col-6" style="float: left;">
                    <p class="assinatura_supervisao">Supervisão de Obras</p>
                </div>
                <div class="col-6" style="float: right;">
                    <span class="assinatura_gerencia_span" style="position: relative; bottom: -80px;">AFFONSO COAN FILHO</span><br>
                    <p class="assinatura_gerencia">Gerência de Obras</p>
                </div>
            </div>
        </div>
    </body>
</html>