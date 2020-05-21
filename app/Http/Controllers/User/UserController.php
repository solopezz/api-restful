<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        //se usa el metodo del trait ApiResponse
        return $this->showAll(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


       $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => ' required|min:6|confirmed'
        ]);

        $userData = $request->all();
        $userData['password'] = bcrypt($request->password);
        $userData['verified'] = User::NOT_VERIFIED;
        $userData['varification_token'] = User::genereteVerificationToken();
        $userData['admin'] = User::REGULAR;

        $user = User::create($userData);

        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */  //implementamos toute model binding User $users
    public function show(User $user)
    {
        return $this->showOne($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            //El email actual del usaurio lo ignora $user->id
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::ADMIN . ',' . User::REGULAR,
        ]);

        //si hay en el request el campo name se actualiza
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        //si email es mandado y el email es diferente al que esta registrado se actualiza
        if ($request->has('email')  && $user->email != $request->email) {
            //el usuario pasa a no verificado
            $user->verified = User::NOT_VERIFIED;
            //se vuelve a generar el token
            $user->varification_token = User::genereteVerificationToken();
            //se actualiza el email
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            //si el usuario no esta verificado retorna el siguiente error
            if (!$user->isVerified()) {
                return $this->errorResponse('Unicamente los usuarios verificaods pueden cambiar su valor a administrador', 409);
            }
            //se acutualiza
            $user->admin = $request->admin;
        }
        //si no hay ningun cambio en el modelo user retorna el error siguiente
        if (!$user->isDirty()) {
            return $this->errorResponse('Se debe de especificar al menos un cambio', 422);
        }

        $user->save();

        return $this->showOne($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        //se usa metodo showOne del trait
        return $this->showOne($user, 200);
    }
}
