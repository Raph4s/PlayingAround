<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;

#[Table("user_resets", keyType: "string", incrementing: false)]
class UserReset extends Model
{
    protected $fillable = ["id", "user_id", "token", "code", "expire_at"];
}
