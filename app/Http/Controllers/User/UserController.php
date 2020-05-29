<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Mail\UserVeryfy;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{

    public function __construct()
    {
        //Proteccion mas segura ya que es con un usario de nuestra base de datos
        $this->middleware('auth:api')->except(['index', 'show', 'verify']);

        //aqui protegemos algunas rutas con client.credentials
        $this->middleware('client.credentials')->only(['index', 'show']);

        $this->middleware('transform.input:'.UserTransformer::class)->only(['store', 'resend']);
    }

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

        //se usa metodo showOne del trait
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
        //se usa metodo showOne del trait
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

        //se usa metodo showOne del trait
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

    public function verify($token)
    {

        $user = User::where('varification_token', $token)->firstOrFail();

        $user->verified = User::VERIFIED;
        $user->varification_token = null;

        $user->save();

        return $this->showMessage('El usuario a sido verificado');
    }

    public function resend(User $user)
    {

        if ($user->isVerified()) {
            return $this->errorResponse('Este usaurio ya fue verificado', 422);
        }
        //Si hay algun problema al enviar el email por problemas terceros se vuelve a intentar 5 veces con una espera de 1000 mili segundos cada una en caso de que no se pueda lanza el error correspondiente
        retry(5, function() use($user) {
            Mail::to($user)->send(new UserVeryfy($user));
        },1000);

        return $this->showMessage('El correo de verificacion se ha enviado');
    }
}
