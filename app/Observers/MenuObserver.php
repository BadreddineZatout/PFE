<?php

namespace App\Observers;

use App\Models\Menu;

class MenuObserver
{
    public function created(Menu $menu)
    {
        $menu->update([
            'created_at' => $menu->date
        ]);
    }
}
