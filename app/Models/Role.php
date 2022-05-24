<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const ADMIN = 1;
    const MINISTER = 2;
    const DECIDER = 3;
    const AGENT_RESTAURATION = 4;
    const AGENT_HEBERGEMENT = 5;
    const AGENT_TRANSPORT = 6;
    const AGENT_INCIDENT = 7;
    const DRIVER = 8;
    const STUDENT = 9;
}
