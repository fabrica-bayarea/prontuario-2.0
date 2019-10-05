<?php

namespace App\Http\Controllers;

use App\Agendamento;
use App\Aluno;
use App\Paciente;
use App\Prontuario;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agendamentos = Agendamento::all();
        $alunos = Aluno::orderBy('tx_nome', 'asc')->get();
        $pacientes = Paciente::orderBy('nome', 'asc')->get();

        return view('agendamento.index', compact('agendamentos','alunos','pacientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            if (!empty($request['id'])) {
                if (!empty($request['paciente_id'])) {
                    return $this->update($request, $request['id']);
                } else {
                    return $this->edit($request, $request['id']);
                }
            }

            $aluno = Aluno::find($request->aluno_id);
            $paciente = Paciente::find($request->paciente_id);
            $agendamento = new Agendamento();

            $agendamento->title       = $aluno->tx_nome . " - " . $paciente->nome;
            $agendamento->color       = '#f8ac59';
            $agendamento->aluno_id    = $request->aluno_id;
            $agendamento->paciente_id = $request->paciente_id;
            $agendamento->status_id   = 1;
            $agendamento->start       = $request->date . " " . $request->start;
            $agendamento->end         = $request->date . " " . $request->end;

            $checkAgendamento = $this->checkAgendamento($agendamento->aluno_id, $agendamento->start, $agendamento->end);

            if (count($checkAgendamento) == 0) {
                if ($request->prontuario_id == null) {
                    $prontuario = (new ProntuarioController())->createByAgendamento($request);
                }
                $agendamento->save();
                Session::flash('success', 'Operação realizada com sucesso');
                return redirect()->route('agendamento.index');
            } else {
                Session::flash('error', 'Já existe agendamento para o terapeuta no intervalo de tempo informado!');
                return redirect()->route('agendamento.index');
            }
        } catch (\Exception $e) {
            throw new \exception('Não foi possível realizar o agendamento!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {

            $agendamento = Agendamento::find($id);

            $agendamento->start = $request->start;
            $agendamento->end   = $request->end;

            $agendamento->save();
            return redirect()->route('agendamento.index');
        } catch (\Exception $e) {
            throw new \exception('Não foi possível alterar o agendamento!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $aluno = Aluno::find($request->aluno_id);
            $paciente = Paciente::find($request->paciente_id);
            $agendamento = Agendamento::find($id);

            $agendamento->title       = $aluno->tx_nome . " - " . $paciente->nome;
            $agendamento->paciente_id = $request->paciente_id;
            $agendamento->aluno_id    = $request->aluno_id;
            $agendamento->start       = $request->date . " " . $request->start;
            $agendamento->end         = $request->date . " " . $request->end;

            if ($request->prontuario_id == null) {
                $prontuario = (new ProntuarioController())->createByAgendamento($request);
            }

            $agendamento->save();
            Session::flash('success', 'Operação realizada com sucesso');
            return redirect()->route('agendamento.index');
        } catch (\Exception $e) {
            throw new \exception('Não foi possível alterar o agendamento!');
        }
    }

    public function changeStatus($id, $status_id)
    {
        try {

            $agendamento = Agendamento::find($id);

            $agendamento->status_id   = $status_id;
            switch ($status_id) {
                case 2:
                    $agendamento->color       = '#1ab394';
                    break;
                case 3:
                    $agendamento->color       = '#ed5565';
                    break;
            }

            $agendamento->save();
            Session::flash('success', 'Operação realizada com sucesso');
            return redirect()->route('agendamento.index');
        } catch (\Exception $e) {
            throw new \exception('Não foi possível alterar o status do agendamento!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $agendamento = Agendamento::where('id', $id)->first();

            $agendamento->delete();
            Session::flash('success', 'Operação realizada com sucesso');
            return redirect()->route('agendamento.index');
        } catch (\Exception $e) {
            throw new \exception('Não foi possível excluir o agendamento!');
        }
    }

    public function findById($id)
    {
        $agendamento = Agendamento::find($id);

        return ['agendamento' => $agendamento];
    }

    private function checkAgendamento($aluno_id, $start, $end)
    {
        return DB::table('agendamentos')
            ->where(function ($query) use ($start, $end) {
                $query->where('start', '<', $start)
                    ->where('end', '>', $start)
                    ->orWhere('start', '<', $end)
                    ->where('end', '>', $end)
                    ->orWhereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end]);
            })->where(function ($query) use ($aluno_id) {
                $query->where('aluno_id', $aluno_id);
            })->whereBetween('status_id', [1, 2])
            ->get();
    }


}
