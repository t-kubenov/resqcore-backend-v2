<?php

namespace App\Menu;

use App\Tenant\User;
use Cache;

class MenuService
{
    public function buildFor(User $user): array
    {
        $menu = config('menu');

        return Cache::remember("menu:branch:{$user['branch_id']}", 3600, fn () =>
            $this->removeRestrictedMenuItems($menu, $user)
        );
    }

    private function removeRestrictedMenuItems($menu, $user): array
    {
        foreach($menu as $key => $item){

            if (!empty($item['children'])){
                $menu[$key]['children'] = $this->removeRestrictedMenuItems($item['children'], $user);
            }

            if (!empty($item['can']) && in_array($user['role']['name'], $item['can']))
            {
                unset($menu[$key]);
            }

            unset($menu[$key]['can']);
        }

        return array_values($menu);
    }
}
