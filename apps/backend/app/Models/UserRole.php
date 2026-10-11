<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;

#[Table("user_roles")]
class UserRole extends Model
{
    protected $fillable = ["user_id", "role_id"];
}
