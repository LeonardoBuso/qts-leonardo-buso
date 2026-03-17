<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request  )
    {
        $users = User::all(); // retorna todos os usuários que estão cadastrados
        $currentPage = $request->query('current_page', 1);
        $regsPerPage = 3;

        $skip = ($currentPage - 1 ) * $regsPerPage;

        $users = User::skip($skip)->take($regsPerPage)->orderByDesc('id')->get(); // mostra de 3 em 3 registros

        return response()->json($users->toResourceCollection(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request) // cadastro do usuário
    {
        $data = ($request->validated());

        try {
            $user = new User(); // cria o usuário
            $user ->fill($data);
            $user->password = Hash::make(123);
            $user->save();

            return response()->json($user->toResource(), 201  );
        } catch (\Exception $th) {
            return response()->json(array(
                'message' => 'Falha ao inserir   usuário!'
            ), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try { // TryCatch é uma maneira de manipular a saída de dados, nesse caso em relação ao usuário não encontrado. Assim, ao rodar no Insomnia, o erro 404 e a mensagem são colocados na tela
            $user = User::findOrFail($id); // acha o usuário que corresponde ao ID situado
            return response()->json($user->toResource(), 200);
        } catch (\Exception $th) {
            return response()->json(array(
                'message' => 'Falha ao buscar usuário!'
            ), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->validated());

            return response()->json($user->toResource(), 200);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'Falha ao alterar usuário!'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $removed = User::destroy($id);
            if (!$removed) {
                throw new Exception();
            }

            return response()->json(null, 204);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'Falha ao remover o usuário!'
            ], 400);
        }
    }
}
