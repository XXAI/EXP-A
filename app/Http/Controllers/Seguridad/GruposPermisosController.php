<?php

namespace App\Http\Controllers\Seguridad;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use \DB, \Response, \Exception; 

use App\Http\Controllers\Controller;
use App\Models\Seguridad\GrupoPermiso;

class GruposPermisosController extends Controller
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
        if(isset($params["all"])){
            if(isset($params["permisos"])){
                return response()->json(["data"=>GrupoPermiso::with(["permisos"])->get()]);
            } else {
                return response()->json(["data"=>GrupoPermiso::all()]);
            }
            
        } else {
            if(!isset($params['pageSize'])){
                $params['pageSize'] = 1;
            }
            
           
            $grupos = GrupoPermiso::select();
            if(isset($params['orderBy']) && trim($params['orderBy'])!= ""){
                $sortOrder = 'asc';
                if(isset($params['sortOrder'])){
                    $sortOrder = $params['sortOrder'];
                }
    
                $grupos = $grupos->orderBy($params['orderBy'],$sortOrder);
            }
            if(isset($params['filter']) && trim($params['filter'])!= ""){
                $grupos = $grupos->where("nombre","LIKE", "%".$params['filter']."%");
            }
            
            $grupos = $grupos->paginate($params['pageSize']);
            
            return response()->json($grupos);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'nombre' => ['required']
        ];

        $messages = [
            'required' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            return  response()->json($validator->messages(), 409);
        }
        try {
            $object = GrupoPermiso::create([
                'nombre'=> $request['nombre']
            ]);

            $object->id;

            return $object;
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
        $rules = [
            'nombre' => ['required']
        ];

        $messages = [
            'required' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            return  response()->json($validator->messages(), 409);
        }
        try {

            $object = GrupoPermiso::find($id);

            if(!$object){
                throw new Exception("No se puede editar un registro inexistente",404);
            }           
            
            $object->nombre = $request['nombre'];
            $object->save();

            return $object;
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
            $object = GrupoPermiso::find($id);
            if(!$object){
                throw new Exception("No de puede borrar un registro inexistente",404);
            }

            $object = GrupoPermiso::destroy($id);
            return Response::json(['data'=>$object],200);
        } catch (Exception $e) {
            return Response::json(['message' => $e->getMessage()], HttpStatusCodes::parse($e->getCode()));
        }
    }
}
