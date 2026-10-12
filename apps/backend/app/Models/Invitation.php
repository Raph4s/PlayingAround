<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table("invitations", keyType: "string", incrementing: false)]
class Invitation extends Model
{
    protected $fillable = [
        "id",
        "email",
        "invited_by",
        "role_id",
        "expire_at",
        "accepted_at",
    ];
}
