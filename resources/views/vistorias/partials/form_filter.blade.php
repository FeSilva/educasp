<div class="modal  fade bd-example-modal-lg" tabindex="-1" role="dialog" id="filterModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Filtro de Vistoria</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            @component('components._form',[
                'method' => 'GET',
                'action' => '',
                'attributes' => 'enctype=multipart/form-data accept=application/pdf'
            ])
                <div class="modal-body">   
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="form-group">
                                <label class="form-label"> Tipo de Busca por data</label>
                                <select name='type_filter' class='form-control type_filter'>
                                    <option value=''> Selecione um tipo de Busca </option>
                                    <option value='fde'>Envio para FDE</option>

                                </select>
                            </div>
                        </div>
                        <div class="form_hidden" id="vistoria">
                            <div class="col-md-4 text-center">
                                <div class="form-group">
                                    <label>Data de Inicio</label>
                                    <input type='date' name='dt_ini' id='dt_ini' class='form-control'>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="form-group">
                                    <label>Data de Fim</label>
                                    <input type='date' name='dt_fim' id='dt_fim' class='form-control'>
                                </div>
                            </div>
                        </div>
                        <div class="form_hidden" id="fde">
                            <div class="col-md-8 text-center">
                                <label class="form-label"> Competencia</label>
                                <input type='text' name='competencia' id='competencia' class='form-control'>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            @endcomponent
        </div>
    </div>
</div>

@push('scripts')
    <script text="text/javascript">
        $(document).ready(function() {
            $(".form_hidden").hide();
            $("#competencia").mask('99',{placeholder:"mm"});
        });

        $(".type_filter").change(function() {
            var type = $(this).val();
            switch (type) {
                case 'fde': 
                    $("#vistoria").hide();
                    $("#fde").show();
                    break;
                case 'vistoria':
                    $("#vistoria").show();
                    $("#fde").hide(); 
                    break;
                default:    
                    alert('Selecione um tipo de Busca');
                    break;
            }
        });
    </script>
@endpush

