@extends('layouts.app')
@section('content-title', 'Agendamentos')
@section('content')

@section('styles')
    <link href="{{ mix('/css/inspinia.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
    <link href='{{asset('/js/plugins/fullcalendar/core/main.css')}}' rel='stylesheet' />
    <link href='{{asset('/js/plugins/fullcalendar/daygrid/main.css')}}' rel='stylesheet' />
    <link href='{{asset('/js/plugins/fullcalendar/timegrid/main.css')}}' rel='stylesheet' />
    <link href='{{asset('/js/plugins/fullcalendar/list/main.css')}}' rel='stylesheet' />
    <style>
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
    </style>
@endsection

@section('content')
    <div class="col-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Consultas agendadas</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content" style="">
                <div id='calendar'></div>
            </div>
        </div>
    </div>

{{-- Modal agendamento--}}
    <div class="modal inmodal" id="modalAgendamento" tabindex="-1" role="dialog"
         style="display: none; padding-right: 14px;">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                            class="sr-only">Close</span></button>
                    <i class="fa fa-calendar modal-icon"></i>
                    <h4 class="modal-title">Realizar agendamento</h4>
                </div>
                <div class="modal-body">
                    <div class="ibox-content">
                        @csrf
                        <form action="{{ route('agendamento.store') }}">
                            <input type="hidden" id="id" name="id">
                            <div class="form-group row"><label class="col-sm-4 col-form-label">Paciente</label>
                                <div class="col-sm-8">
                                    <select class="form-control m-b" name="paciente" required>
                                        <option></option>
                                        @foreach($pacientes as $p)
                                            <option>{{$p->nome}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row"><label class="col-sm-4 col-form-label">Terapeuta</label>
                                <div class="col-sm-8">
                                    <select class="form-control m-b" name="aluno" required>
                                        <option></option>
                                        @foreach($alunos as $a)
                                            <option>{{$a->tx_nome}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row"><label class="col-sm-4 col-form-label">Início da consulta</label>
                                <div class="col-sm-8">
                                    <input type="time" class="form-control" id="start" name="start" required>
                                    <input type="hidden" id="date" name="date">
                                </div>
                            </div>
                            <div class="form-group row"><label class="col-sm-4 col-form-label">Términdo da consulta</label>
                                <div class="col-sm-8">
                                    <input type="time" class="form-control" id="end" name="end" required>
                                </div>
                            </div>
                            <div class="form-group row"><label class="col-sm-4 col-form-label">Selecione a cor</label>
                                <div class="col-sm-8">
                                    <select class="form-control m-b" name="cor">
                                        <option style="color:#1ab394;" value="#1ab394">Verde</option>
                                        <option style="color:#1c84c6;" value="#1c84c6">Azul</option>
                                        <option style="color:#23c6c8;" value="#23c6c8">Verde claro</option>
                                        <option style="color:#f8ac59;" value="#f8ac59">Laranjado</option>
                                        <option style="color:#ed5565;" value="#ed5565">Vermelho</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="ol-lg-12">
                                    <button class="btn btn-sm btn-outline-primary" type="submit">Agendar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}" charset="utf-8"></script>
    <script src="{{ mix('/js/manifest.js') }}" charset="utf-8"></script>
    <script src="{{ mix('/js/vendor.js') }}" charset="utf-8"></script>
    <script src="{{ mix('/js/inspinia.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/jasny-bootstrap.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/select2.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/choose.jquery.js') }}" charset="utf-8"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <script src='{{asset('/js/plugins/fullcalendar/core/main.js')}}'></script>
    <script src='{{asset('/js/plugins/fullcalendar/interaction/main.js')}}'></script>
    <script src='{{asset('/js/plugins/fullcalendar/daygrid/main.js')}}'></script>
    <script src='{{asset('/js/plugins/fullcalendar/timegrid/main.js')}}'></script>
    <script src='{{asset('/js/plugins/fullcalendar/list/main.js')}}'></script>
    <script src='{{asset('/js/plugins/fullcalendar/core/locales/pt-br.js')}}'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                navLinks: true, // can click day/week names to navigate views
                businessHours: true, // display business hours
                editable: true,
                locale: 'pt-br',
                events: {!! $agendamentos !!},
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // don't let the browser navigate
                    alert(info.event.id);
                    $('#id').val(info.event.id);
                    $('#modalAgendamento').modal('show');
                },
                selectable: true,
                select: function(info) {
                    $('#modalAgendamento').modal('show');
                    $('#date').val(info.startStr);
                }
            });

            calendar.render();
        });
    </script>
@endsection
