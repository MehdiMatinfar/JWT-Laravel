<?php

namespace App\Http\Controllers;

use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class AuthController extends Controller
{

   public function userRegister(Request $request)
   {

      $user = User::create([

         'name' => $request->input('name'),
         'email' => $request->input('email'),
         'password' => Hash::make($request->input('password'))


      ]);



      return $user;
   }
   public function userLogin(Request $request)
   {

      if (!Auth::attempt($request->only('email', 'password'))) {
         return response(['message' => 'invalid data'], HttpFoundationResponse::HTTP_UNAUTHORIZED);
      }

      $user =  Auth::user();

      $token = $user->createToken('token')->plainTextToken;
      $cookie = cookie('jwt', $token, 24 * 60 * 2);
      return response(['message' => 'login_successfully'])->withCookie($cookie);
   }



   public function user()
   {


      return Auth::user();
   }

   public function logOut()
   {


      $cookie = Cookie::forget('jwt');
      return response(['message' => 'logout_successfully'])->withCookie($cookie);
   }
}
