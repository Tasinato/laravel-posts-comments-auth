<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}

//O Laravel utiliza hasMany() para relacionamentos um-para-muitos e belongsTo() para o relacionamento inverso.
//O método fillable define os campos que podem ser preenchidos em massa, protegendo contra atribuição em massa indesejada.
//O fillable somente para title e content.
//A identificação do autor deve ser atribuída por meio do usuário autenticado e dos relacionamentos Eloquent, não por um user_id enviado livremente pelo formulário.