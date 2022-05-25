<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeFeedback extends Model
{
    use HasFactory;

    const TYPE_NUMBER = 3;

    const RESTAURATION_TYPE = 1;
    const ACCOMMODATION_TYPE = 2;
    const TRANSPORT_TYPE = 3;
}
