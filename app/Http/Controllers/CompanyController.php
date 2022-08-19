<?php

namespace App\Http\Controllers;

use App\Models\CompanyUsers;
use App\Models\Users;

use Illuminate\Http\Request;

class CompanyController extends Controller
{

    private $model;

    public function __construct(CompanyUsers $modelCompany, Users $users)
    {
        $this->model = $modelCompany;
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companys = $this->model->get();
        
        return view('company.index', compact('companys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fiscais = $this->users->where("ativo", 1)->where("grupo", 'fiscal')->orderBy('name', 'asc')->get();
        return view('company.create', compact('fiscais'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->model->create($request->except('_token'));
        $return = $this->metaDadoById($id);
        $company = $return['company'];
        $fiscais = $return['fiscais'];

        return redirect()
        ->back()
        ->with('success', 'Company criado !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('company.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $return = $this->metaDadoById($id);
        $company = $return['company'];
        $fiscais = $return['fiscais'];
        return view('company.edit', compact('company', 'fiscais'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->model->where('company_id', $request->input('company_id'))->update($request->except('_token'));
        $return = $this->metaDadoById($request->input('company_id'));
        $company = $return['company'];
        $fiscais = $return['fiscais'];

        return redirect()
        ->back()
        ->with('success', 'Company Atualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function metaDadoById($id)
    {
        $company = $this->model->find($id);
        $fiscais = $this->users->where("ativo", 1)->where("grupo", 'fiscal')->orderBy('name', 'asc')->get();
        return [
            'company' => $company,
            'fiscais' => $fiscais
        ];
    }
}
