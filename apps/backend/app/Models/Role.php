<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;

#[Table("roles")]
class Role extends Model
{
    protected $fillable = ["name"];
}
