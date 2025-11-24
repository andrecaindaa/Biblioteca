<?php

namespace App\Policies;

use App\Models\Requisicao;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RequisicaoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
   public function viewAny(User $user)
{
    return true; // Admin vê tudo, cidadão vê as suas
}
/*
public function view(User $user, Requisicao $req)
{
    if ($user->role->nome === 'admin') {
        return true;
    }

    return $req->user_id === $user->id;
}
*/
public function view(User $user, Requisicao $req)
{
    // Garantir que existe role carregado
    $user->loadMissing('role');

    if ($user->isAdmin()) {
        return true;
    }

    return $req->user_id === $user->id;
}

}
