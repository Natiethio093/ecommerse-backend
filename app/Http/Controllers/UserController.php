<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

  function Register(Request $req)
  {
  
    $validatedData = Validator::make($req->all(), [
      'firstname' => 'required|max:100',
      'lastname' => 'required|max:100',
      'email' => 'required|email|unique:users,email',//checks the email is unique from the users table
      'phone' => 'required|digits:10',
      'password' => 'required|min:8',
      'confirmpassword' => 'required|min:8'
  ]);
    
  if ($validatedData->fails()) {

    return response()->json([
   'validate_err' => $validatedData->messages(),

 ]);
} 

    // if($req->password != $req->conpassword){
    //   return response()->json([
    //     "error" => "Password conformation failed!"
    //   ],401);
    // }
    
    $user=new User;
    $user->firstname=$req->firstname;
    $user->lastname=$req->lastname;
    $user->email=$req->email;
    $user->phone=$req->phone;
    $user->password=Hash::make($req->password);
    $user->save();

    // return $user;
    return response()->json([
      "status" => 200,
      'user' =>  $user,
      'token' => $user->createToken("API TOKEN")->plainTextToken,
    ]);
  }

 function Login(Request $req){

  $credentials = request(['email', 'password']);
    
  if (!Auth::attempt($credentials)) {
    // return response()->json(['error' => 'Unauthorized'], 401);
    return response()->json([
      "status" => 400, 
      "error" => "Email or password is incorrect!"
    ]);
}
  
    $user = User::where('email', $req->email)->first();
  
  if ($user && Hash::check($req->password, $user->password)) {

    $payload = [
      'id' => $user->id,
      'email' => $user->email,
      'username' => $user->firstname // Assuming the user name is stored in the 'name' column
  ];

    $token = $user->createToken('API Token')->plainTextToken;
    // $token = $user->createToken('Token Name')
        // ->withClaims($payload)
        // ->accessToken;
    //  return $user;
     return response()->json([
      'status' => 200,
      'message' => 'User Logged in Successfully!',
      'token_type' => 'Bearer',
      'token' => $token,
      // 'userlogin' => $user
    ]);
  }
  
  
}
public function getUserData()
    {
        $user = Auth::user();

        return response()->json([
            'userlogin' => $user,
        ]);
    }

 }

