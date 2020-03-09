<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use \DB, \Response, \Exception; 

use App\Models\Auth\Usuario;

class UsuariosController extends Controller
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
            
            return response()->json(["data"=>Usuario::all()]);
        } else {
            if(!isset($params['pageSize'])){
                $params['pageSize'] = 1;
            }
            
           
            $items = Usuario::select();
            if(isset($params['orderBy']) && trim($params['orderBy'])!= ""){
                $sortOrder = 'asc';
                if(isset($params['sortOrder'])){
                    $sortOrder = $params['sortOrder'];
                }
    
                $items = $items->orderBy($params['orderBy'],$sortOrder);
            }
            if(isset($params['filter']) && trim($params['filter'])!= ""){
                $items = $items->where("username","LIKE", "%".$params['filter']."%");
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
        $username = $request['username'];

        $rules = [
            'username' => ['required', Rule::unique('usuarios')->where(function($query) use ($username){
                return $query->where('servidor_id',env('SERVIDOR_ID'))->where('username',$username);
            })],
            'password' => 'required',
            'roles'=>'required'
        ];

        $messages = [
            'required' => 'required',
            'unique' => 'unique'
        ];
        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            return  response()->json($validator->messages(), 409);
        }

        return Usuario::create([
            'id'=>env('SERVIDOR_ID').'1',
            'servidor_id' => env('SERVIDOR_ID'),
            'username'=> $request['username'],
            'password' => Hash::make($request['password']),
        ]);
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
    public function edit($id)
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
}
