<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Notifications\SendPasswordEmail;
use JWTFactory;
use JWTAuth;
use Validator;
use Response;
use App\Client;
use Illuminate\Support\Facades\DB;


class APIRegisterController extends Controller
{
    public function register(Request $request)
    {  
        \Config::set('auth.providers.users.model', \App\User::class);

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users|regex:/(.*)forhey\.com$/i',
            'name' => 'required',
            'password'=> 'required',
            'phone_number'=>'required',
            'address'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),    
            'phone_number'=>$request->get('phone_number'),
            'address'=>$request->get('address')
        ]);

        $token = JWTAuth::fromUser($user);
        
        return Response::json(compact('token'));
    }




    public function create (Request $request)
    {
        \Config::set('auth.providers.users.model', \App\Client::class); 
       
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:clients',
            'name' => 'required',
            'phone_number'=>'required',
            'location'=>'required',
            'street_name'=>'required',
            'house_number'=>'required',
            'gps_coordinates'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
            
        $password = str_random(8);
        $hashed_random_password = bcrypt($password);

        $client = Client::create([
            'marketer_id'=>$request-> get('marketer_id'),
            'email'=>$request->get('email'),
            'name' =>$request->get('name'),
            'password'=> $hashed_random_password,
            'phone_number'=>$request->get('phone_number'),
            'location'=>$request->get('location'),
            'street_name'=>$request->get('street_name'),
            'house_number'=>$request->get('house_number'),
            'gps_coordinates'=>$request->get('gps_coordinates'),
        ]);

       $client->notify(new SendPasswordEmail($password));

   
   
  


}
  public function index (Request $request)
    {
        $clients = DB::table('clients')->get();

        return Response::json(compact('clients'));


    } 
    public function details(Request $request){
                $client = DB::table('clients')->where('id','1')->first();
                 return Response::json(compact('client'));
 



    }   
}