@extends('layouts.app')
@section('content-title', 'Grupos')
@section('content')

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Dados Gerais</h5>
                    </div>
                    <div class="ibox-content">
                        <a href="{{ route('grupos.create') }}" class="btn-novo btn btn-success">
                            <i class="fa fa-plus"></i>&nbsp;Novo
                        </a>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered dataTable">
                                <thead>
                                    <tr>
                                        <th>Ações</th>
                                        <th>Nome</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($aItens as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('grupos.edit', base64_encode($item->id)) }}"
                                               class="btn btn-primary" title="Editar">
                                                <span class="fa fa-edit"></span>
                                            </a>
                                            <a href="{{ route('grupos.delete', base64_encode($item->id)) }}"
                                               class="btn btn-danger link-excluir" title="Excluir">
                                                <span class="fa fa-trash"></span>
                                            </a>
                                        </td>
                                        <td>{{ $item->tx_nome }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
