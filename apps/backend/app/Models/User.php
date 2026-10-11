<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Table("users")]
class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes;

    protected $fillable = ["email", "fullname", "password"];

    protected $hidden = ["password"];

    protected $casts = [
        "password" => "hashed",
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, "user_roles");
    }
}
