<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const ADMIN = 1;
    const STUDENT = 2;
    const DECIDER = 3;
    const AGENT_RESTAURATION = 4;
    const DRIVER = 5;
    const MINISTER = 6;
    const AGENT_HEBERGEMENT = 7;
    const AGENT_TRANSPORT = 8;
}
