<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Access\Gate as Gate;
use App\SupervisorModel as Supervisor;
use App\User as User;
use App\LinhaTeoricaModel as Line;
use Exception;

class SupervisorController extends Controller

{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws Exception
     */
    public function index()
    {
        if(!\Gate::allows('Admin')){
            abort(403, "Página não autorizada! Você não tem permissão para acessar nessa página!");
        }

        try {
            // Retorna todos os Supervisors que tem o status Ativo.
            $supervisors = Supervisor::all();
            $id_line = $supervisors->id_theoretical_line;
            dd($id_line);
            $id_user = $supervisors->id_user;

            $supervisor_user = User::find($id_user);

            $line_supervisor = Line::find($id_line);

            return view('supervisor.index', compact(['supervisors', 'supervisor_user', 'line_supervisor'],
                [$supervisors, $supervisor_user, $line_supervisor])
            );
        } catch (\Exception $e) {
            echo $e; die;
            throw new Exception('Não foi possível trazer os dados dos Supervisors !');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminfate\Http\Response
     */
    public function create()
    {
        if(!\Gate::allows('Admin')){
            abort(403, "Página não autorizada! Você não tem permissão para acessar nessa página!");
        }

        return view('perfil.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        if(!\Gate::allows('Admin')){
            abort(403, "Página não autorizada! Você não tem permissão para acessar nessa página!");
        }
        try {
            if (!empty($request['id_perfil'])) {
                try {
                    Perfil::find($request['id_perfil'])->update($request->input());
                    return redirect()->route('supervisor.index');
                } catch (Exception $e) {
                    throw new exception('Não foi possível alterar o registro do Demandante ' . $request->nome . ' !');
                }
            }
            $supervisors = new Perfil();
            $supervisors->nome = $request->nome;
            $supervisors->status = 'A';
            $supervisors->save();

            return redirect()->route('supervisor.index');
        } catch (Exception $e) {
            throw new exception('Não foi possível salvar o Perfil' . $request->nome . ' !');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws Exception
     */
    public function edit($id)
    {
        if(!\Gate::allows('Admin')){
            abort(403, "Página não autorizada! Você não tem permissão para acessar nessa página!");
        }

        try {
            $supervisors = Perfil::find($id);
            return view('perfil.edit', compact('supervisors', $supervisors));

        } catch (Exception $e) {
            throw new exception('Não foi possível recuperar os dados do perfil ' . $supervisors->tx_nome . ' !');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // está sendo feito no store.
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function destroy($id)
    {
        if(!\Gate::allows('Admin')){
            abort(403, "Página não autorizada! Você não tem permissão para acessar nessa página!");
        }

        try {
            $supervisors = Perfil::find($id);
            $supervisors->status = 'I';
            $supervisors->save();
            return redirect()->route('supervisor.index');
        } catch (Exception $e) {
            throw new exception('Não foi possível excluir o registro do Perfil ' . $supervisors->tx_nome . ' !');
        }
    }
}
