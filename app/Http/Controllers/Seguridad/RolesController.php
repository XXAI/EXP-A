<?php

namespace App\Http\Controllers\Seguridad;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use \DB, \Response, \Exception; 

use App\Http\Controllers\Controller;
use App\Models\Seguridad\Rol;


class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->input();
        if(isset($params["all"])){
            
            return response()->json(["data"=>Rol::all()]);
        } else {
            if(!isset($params['pageSize'])){
                $params['pageSize'] = 1;
            }
            
           
            $items = Rol::select();
            if(isset($params['orderBy']) && trim($params['orderBy'])!= ""){
                $sortOrder = 'asc';
                if(isset($params['sortOrder'])){
                    $sortOrder = $params['sortOrder'];
                }
    
                $items = $items->orderBy($params['orderBy'],$sortOrder);
            }
            if(isset($params['filter']) && trim($params['filter'])!= ""){
                $items = $items->where("nombre","LIKE", "%".$params['filter']."%");
            }
            
            $items = $items->paginate($params['pageSize']);
            
            return response()->json($items);
        }
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
        $rules = [

            'nombre' => ['required'],
            'permisos' => ['required','array'],
            'permisos.*' => ['exists:App\Models\Seguridad\Permiso,id']
        ];

        $messages = [
            'required' => 'required',
            'array' => 'array',
            'exists' => 'exists'
        ];
        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            return  response()->json($validator->messages(), 409);
        }

        DB::beginTransaction();
        try {
            $rol = Rol::create([
                'nombre'=> $request['nombre']
            ]);

            $rol->permisos()->sync($request['permisos']);
            DB::commit();
        }catch (\Exception $e) {
            DB::rollback();
            return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
        }
        
        return $rol;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $object = Rol::find($id);

            if(!$object){
                throw new Exception("Registro inexistente",404);
            } 
            $object->permisos;

            return $object;
        } catch (Exception $e) {
            return Response::json(['message' => $e->getMessage()], HttpStatusCodes::parse($e->getCode()));
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
        $rules = [

            'nombre' => ['required'],
            'permisos' => ['required','array'],
            'permisos.*' => ['exists:App\Models\Seguridad\Permiso,id']
        ];

        $messages = [
            'required' => 'required',
            'array' => 'array',
            'exists' => 'exists'
        ];

        DB::beginTransaction();
        try {

            $rol = Rol::find($id);

            if(!$rol){
                throw new Exception("Registro inexistente",404);
            } 

            $validator = Validator::make($request->all(), $rules,$messages);

            if ($validator->fails()) {
                return  response()->json($validator->messages(), 409);
            }
        
            $rol->nombre = $request['nombre'];
            $rol->permisos()->sync($request['permisos']);
            $rol->save();
            DB::commit();
        }catch (\Exception $e) {
            DB::rollback();
            return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
        }
        
        return $rol;
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
            $rol = Rol::find($id);
            if(!$rol){
                throw new Exception("No de puede borrar un registro inexistente",404);
            }

            $object = Rol::destroy($id);
            return Response::json(['data'=>$object],200);
        } catch (Exception $e) {
            return Response::json(['message' => $e->getMessage()], HttpStatusCodes::parse($e->getCode()));
        }
    }
}
