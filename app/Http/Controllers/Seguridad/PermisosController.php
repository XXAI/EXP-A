<?php

namespace App\Http\Controllers\Seguridad;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use \DB, \Response, \Exception; 


use App\Http\Controllers\Controller;
use App\Models\Seguridad\Permiso;
use App\Helpers\HttpStatusCodes;

class PermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $params = $request->input();

        if(!isset($params['pageSize'])){
            $params['pageSize'] = 1;
        }
        
       
        $permisos = Permiso::with('grupo');
        if(isset($params['orderBy']) && trim($params['orderBy'])!= ""){
            $sortOrder = 'asc';
            if(isset($params['sortOrder'])){
                $sortOrder = $params['sortOrder'];
            }

            $permisos = $permisos->orderBy($params['orderBy'],$sortOrder);
        }
        if(isset($params['filter']) && trim($params['filter'])!= ""){
            $permisos = $permisos->where("descripcion","LIKE", "%".$params['filter']."%")->orWhere("id","LIKE","%".$params['filter']."%");
        }
        
        $permisos = $permisos->paginate($params['pageSize']);
        
        return response()->json($permisos);
    }  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$username = $request['username'];

        $rules = [
            'descripcion' => ['required'],
            'grupo_id' => ['required','exists:App\Models\Seguridad\GrupoPermiso,id'],
            'su' => 'boolean'
        ];

        $messages = [
            'required' => 'required',
            'exists' => 'exists',
            'boolean' => 'boolean'
        ];
        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            return  response()->json($validator->messages(), 409);
        }
        try {
            $permiso = Permiso::create([
                'id'=>Str::random(16),
                'descripcion'=> $request['descripcion'],
                'grupo_id'=> $request['grupo_id'],
                'su' => isset($request['su'])? $request['su'] : false,
            ]);

            $permiso->id;

            return $permiso;
        } catch (Exception $e) {          
            return Response::json(['message' => $e->getMessage()], HttpStatusCodes::parse($e->getCode()));
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        
        $rules = [
            'id'=> ['required','max:16', Rule::unique('permisos')->ignore($id)],
            'descripcion' => ['required'],
            'grupo_id' => ['required','exists:App\Models\Seguridad\GrupoPermiso,id'],
            'su' => 'boolean'
        ];

        $messages = [
            'max' => 'max',
            'unique' => 'unique',
            'required' => 'required',
            'exists' => 'exists',
            'boolean' => 'boolean'
        ];
        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            return  response()->json($validator->messages(), 409);
        }
        try {

            $permiso = Permiso::find($id);

            if(!$permiso){
                throw new Exception("No se puede editar un registro inexistente",404);
            }           
            
            $permiso->id = $request['id'];
            $permiso->descripcion = $request['descripcion'];
            $permiso->grupo_id = $request['grupo_id'];
            $permiso->su = isset($request['su'])? $request['su'] : false;
            $permiso->save();

            return $permiso;
        } catch (Exception $e) {
            return Response::json(['message' => $e->getMessage()], HttpStatusCodes::parse($e->getCode()));
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
            //$object = Permiso::destroy($id);
            $permiso = Permiso::find($id);
            if(!$permiso){
                throw new Exception("No de puede borrar un registro inexistente",404);
            }

            $object = Permiso::destroy($id);
            return Response::json(['data'=>$object],200);
        } catch (Exception $e) {
            return Response::json(['message' => $e->getMessage()], HttpStatusCodes::parse($e->getCode()));
        }
    }
}
