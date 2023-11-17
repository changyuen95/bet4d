<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Bank;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
class MeController extends Controller
{
    public function me()
    {
        return new UserResource(Auth::user());
    }

    public function bank()
    {
        // return Auth::user()->bank;
    }

    public function update(Request $request)
    {
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['email','required',Rule::unique('users')->where(function ($query) use($id){
                return $query->whereNull('deleted_at')
                            ->where('id','!=',$id);
            })]
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }
        $user = Auth::user();
        $user->fill($request->only('name', 'email'));
        $user->save();

        return response(new UserResource($user));

    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        if(!$user){
            return response(['message' => trans('messages.no_user_found')], 422);
        }
    

        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });
        
        $user->delete();

        
        return response([
            'message' => trans('messages.successfully_deleted_account'),
        ], 200);

    }

}