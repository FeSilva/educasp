<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <a href="http://www.fde.sp.gov.br/?AspxAutoDetectCookieSupport=1" target="_blank">
            <img src="{{ URL::asset('/storage/Logo_fde.jpg') }}" class="img-logo">
        </a>
    </div>
    <div class="sidebar-wrapper">
 
        <ul class="nav">
           
            {{-- Validar active de link --}}
            <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'dashboard') }}">
                    <i class="nc-icon nc-bank"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            @if(Auth()->user()->grupo == 'gestor' || Auth()->user()->grupo == 'analista')
            <li
                class="{{ $elementActive == 'user' || $elementActive == 'usuarios' || $elementActive == 'predios' || $elementActive == 'pi' || $elementActive == 'empreiteiras' ? 'active' : '' }}">

                @php
                    $elementActive == 'user' || $elementActive == 'usuarios' || $elementActive == 'predios' || $elementActive == 'pi' || $elementActive == 'empreiteiras' ? ($show = 'show') : ($show = '');
                @endphp
                <a data-toggle="collapse" aria-expanded="false" href="#parametros">
                    <i class="nc-icon nc-badge"></i>
                    <p>{{ __('Paramêtros') }}<b class="caret"></b></p>
                </a>
                <div class="collapse {{ $show }}" id="parametros">
                    <ul class="nav">

                        <li class="{{ $elementActive == 'usuarios' ? 'active' : '' }}">
                            <a href="{{ route('page.index', 'usuarios') }}">

                                <span class="sidebar-normal">{{ __(' - Usuários') }}</span>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'company' ? 'active' : '' }}">
                            <a href="{{ route('page.index', 'company') }}">
                                <span class="sidebar-normal">{{ __(' - Empresas') }}</span>
                            </a>
                        </li>


                        <li class="{{ $elementActive == 'predios' ? 'active' : '' }}">
                            <a href="{{ route('page.index', 'predios') }}">
                                <span class="sidebar-normal">{{ __(' - Prédios') }}</span>
                            </a>
                        </li>

                        <li class="{{ $elementActive == 'empreiteiras' ? 'active' : '' }}">
                            <a href="{{ route('page.index', 'empreiteiras') }}">
                                <span class="sidebar-normal">{{ __(' - Empreiteiras') }}</span>
                            </a>
                        </li>

                        <li class="{{ $elementActive == 'pi' ? 'active' : '' }}">
                            <a href="{{ route('page.index', 'pi') }}">
                                <span class="sidebar-normal">{{ __(' - PI') }}</span>
                            </a>
                        </li>

                        <li class="{{ $elementActive == 'pi' ? 'active' : '' }}">
                            <a href="{{ route('page.index', 'vistorias/tipos') }}">
                                <span class="sidebar-normal">{{ __(' - Tipo de Vistoria') }}</span>
                            </a>
                        </li>
                        {{-- <li class="{{ $elementActive == 'acesso' ? 'active' : '' }}">
                            <a href="{{ route('page.index', 'user') }}">
                                <span class="sidebar-normal">{{ __('Controle de Acesso ') }}</span>
                            </a>
                        </li> --}}
                    </ul>
                </div>
            </li>
            @endif
            <li
                    class="{{ $elementActive == 'vistoria' || $elementActive == 'vistorias' || $elementActive == 'especificas' || $elementActive == 'unidade-movel' || $elementActive == 'seguranca'|| $elementActive == 'upload-zip' || $elementActive == 'lista_envios' ?  'active' : '' }}">
                @php
                    $elementActive == 'vistoria' || $elementActive == 'vistorias' || $elementActive == 'especificas' || $elementActive == 'unidade-movel' || $elementActive == 'seguranca' || $elementActive == 'upload-zip' || $elementActive == 'lista_envios' ? ($show = 'show') : ($show = '');
                @endphp
                <a data-toggle="collapse" aria-expanded="false" href="#vistorias">
                    <i class="nc-icon nc-single-copy-04"></i>
                    <p>{{ __('Vistorias') }}<b class="caret"></b></p>
                </a>
                <div class="collapse {{ $show }}" id="vistorias">
                    
                    <ul class="nav">
                        <li class="{{ $elementActive == 'vistorias' || $elementActive == 'upload-zip' || $elementActive == 'lista_envios' ? 'active' : '' }}">
                            <a data-toggle="collapse" aria-expanded="false" href="#vistoriasFiscalizacao">
                                <p>__ {{ __('Fiscalização') }}<b class="caret"></b></p>
                            </a>
                            <div class="collapse {{ $show }}" id="vistoriasFiscalizacao">
                                <ul class="nav">

                                    <li class="{{ $elementActive == 'vistorias' ? 'active' : '' }}">
                                        <a href="{{ route('page.index', 'vistorias') }}">
                                            <span class="sidebar-normal">{{ __(' - Cadastro de Vistoria ') }}</span>
                                        </a>
                                    </li>
                                    <li class="{{ $elementActive == 'lista_envios' ? 'active' : '' }}">
                                        <a href="{{ route('page.index', 'listaenvios') }}">
                                            <span class="sidebar-normal">{{ __(' - Lista de Envios ') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li
                            class="{{ $elementActive == 'vistoriaMultiplas' || $elementActive == 'especificas' || $elementActive == 'unidade-movel' || $elementActive == 'seguranca' || $elementActive == 'upload-zipMultiplos' || $elementActive == 'lista_envios_multiplos' ?  'active' : '' }}">
                            @php
                                $elementActive == 'vistoriaMultiplas'  || $elementActive == 'especificas' || $elementActive == 'unidade-movel' || $elementActive == 'seguranca' || $elementActive == 'upload-zipMultiplos' || $elementActive == 'lista_envios_multiplos' ? ($show = 'show') : ($show = '');
                            @endphp
                            <a data-toggle="collapse" aria-expanded="false" href="#vistoriaMultiplas">
                            
                                <p>__ {{ __('Multiplas') }}<b class="caret"></b></p>
                            </a>
                            <div class="collapse {{ $show }}" id="vistoriaMultiplas">
                                <ul class="nav">
                                    <li class="{{ $elementActive == 'vistoriaMultiplas' || $elementActive == 'upload-zipMultiplos' || $elementActive == 'lista_envios_multiplos' ? 'active' : '' }}">
                                        <ul class="nav">
            
                                            <li class="{{ $elementActive == 'vistoriaMultiplas' ? 'active' : '' }}">
                                                <a href="{{ route('page.index', 'vistorias/multiplas') }}">
                                                    <span class="sidebar-normal">{{ __(' - Cadastro de Vistoria Multiplas') }}</span>
                                                </a>
                                            </li>
                                            <li class="{{ $elementActive == 'lista_envios_multiplos' ? 'active' : '' }}">
                                                <a href="{{ route('page.index', 'listaenviosMultiplos') }}">
                                                    <span class="sidebar-normal">{{ __(' - Lista de Envios ') }}</span>
                                                </a>
                                            </li>
                                            <!--<li class="{{ $elementActive == 'especificas' ? 'active' : '' }}">
                                                <a href="">
                                                    <span class="sidebar-normal">{{ __(' - Lista de Envio') }}</span>
                                                </a>
                                            </li>
                                            <li class="{{ $elementActive == 'cadastro-pi' ? 'active' : '' }}">
                                                <a href="">
                                                    <span class="sidebar-normal">{{ __(' - Gerência') }}</span>
                                                </a>
                                            </li>-->
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>

            

            <li
                class="{{ $elementActive == 'protocolos' || $elementActive == 'protocolosMultiplos' || $elementActive == 'os' ? 'active' : '' }}">

                @php
                    $elementActive == 'protocolos' || $elementActive == 'protocolosMultiplos' || $elementActive == 'os' ? ($show = 'show') : ($show = '');
                @endphp
                <a data-toggle="collapse" aria-expanded="false" href="#documents">
                    <i class="nc-icon nc-badge"></i>
                    <p>{{ __('Documentos') }}<b class="caret"></b></p>
                </a>
                <div class="collapse {{ $show }}" id="documents">
                    <ul class="nav">

                        <li class="{{ $elementActive == 'protocolos' || $elementActive == 'protocolosMultiplos' || $elementActive == 'os' ? 'active' : '' }}">
                            @php
                                $elementActive == 'protocolos' || $elementActive == 'protocolosMultiplos' || $elementActive == 'os' ? ($show = 'show') : ($show = '');
                            @endphp
                            <a data-toggle="collapse" aria-expanded="false" href="#protocolos">
                      
                                <p>{{ __('Protocolos') }}<b class="caret"></b></p>
                            </a>
                            <div class="collapse {{ $show }}" id="protocolos">
                                <ul class="nav">

                                    <li class="{{ $elementActive == 'protocolos' ? 'active' : '' }}">
                                        <a href="{{ route('page.index', 'documents/protocolos') }}">
                                            <span class="sidebar-normal">{{ __(' - Protocolo de Fiscalização ') }}</span>
                                        </a>
                                    </li>

                                    <li class="{{ $elementActive == 'protocolosMultiplos' ? 'active' : '' }}">
                                        <a href="{{ route('page.index', 'documents/protocolos/multiplos') }}">
                                            <span class="sidebar-normal">{{ __(' - Protocolos Multiplos ') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </li>
                        </li>

                       <!-- <li class="{{ $elementActive == 'os' ? 'active' : '' }}">
                            <a href="{{ route('page.index', 'documents/os') }}">
                                <span class="sidebar-normal">{{ __(' - OS') }}</span>
                            </a>
                        </li>-->

                    </ul>
                </div>
            </li>
            <li class="{{ $elementActive == 'relatorios' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'medicao') }}">
                    <i class="nc-icon nc-badge"></i>
                    <span class="sidebar-normal">{{ __(' - Medicao') }}</span>
                </a>
            </li>


           <!---<li class="{{ $elementActive == 'logs' ? ($show = 'show active') : ($show = '') }}">
                <a data-toggle="collapse" aria-expanded="false" href="#logs">
                    <i class="nc-icon nc-settings-gear-65"></i>
                    <p>{{ __('Logs') }}<b class="caret"></b></p>
                </a>
                <div class="collapse {{ $show }}" id="logs">
                    <ul class="nav">
                        <li class="{{ $elementActive == 'logs' ? 'active' : '' }}">
                            <a href="{{ route('logs.upload.list') }}">

                                <span class="sidebar-normal">{{ __(' - Uploads') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>--->



            <li class="{{ $elementActive == 'user' || $elementActive == 'profile' ? ($show = 'show') : ($show = '') }}">
                <a data-toggle="collapse" aria-expanded="false" href="#configuracao">
                    <i class="nc-icon nc-settings-gear-65"></i>
                    <p>{{ __('Configurações') }}<b class="caret"></b></p>
                </a>
                <div class="collapse {{ $show }}" id="configuracao">
                    <ul class="nav">
                        <li class="{{ $elementActive == 'profile' ? 'active' : '' }}">
                            <a href="{{ route('usuarios.perfil') }}">

                                <span class="sidebar-normal">{{ __('Perfil') }}</span>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'user' ? 'active' : '' }}">
                            <form action="{{ route('logout') }}" id="formLogOut" method="POST"
                                style="display: none;">
                                @csrf
                            </form>

                            <a href="#" onclick="document.getElementById('formLogOut').submit();">
                                {{ __('Sair') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>






            {{-- <li class="{{ $elementActive == 'icons' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'icons') }}">
                    <i class="nc-icon nc-diamond"></i>
                    <p>{{ __('Icons') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'map' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'map') }}">
                    <i class="nc-icon nc-pin-3"></i>
                    <p>{{ __('Maps') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'notifications' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'notifications') }}">
                    <i class="nc-icon nc-bell-55"></i>
                    <p>{{ __('Notifications') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'tables' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'tables') }}">
                    <i class="nc-icon nc-tile-56"></i>
                    <p>{{ __('Table List') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'typography' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'typography') }}">
                    <i class="nc-icon nc-caps-small"></i>
                    <p>{{ __('Typography') }}</p>
                </a>
            </li>
            <li class="active-pro {{ $elementActive == 'upgrade' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'upgrade') }}" class="bg-danger">
                    <i class="nc-icon nc-spaceship text-white"></i>
                    <p class="text-white">{{ __('Upgrade to PRO') }}</p>
                </a>
            </li> --}}
        </ul>
    </div>
</div>
