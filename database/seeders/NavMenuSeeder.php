<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NavMenu;
use Illuminate\Support\Facades\DB;

class NavMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu_array = [
            [
                'title' => 'Dashboard',
                'icon' => 'fas fa-home',
                'link' => '/page_dashboard',
                'allowed_roles' => json_encode(['1']),
                'parent_menu' => '0'
            ],
            [

                'title' => 'User Management',
                'icon' => 'fas fa-users',
                'link' => '/page_users',
                'allowed_roles' => json_encode(['1']),
                'parent_menu' => '0'

            ],
            [
                'title' => 'Developer Option',
                'icon' => 'fas fa-users',
                'link' => '#',
                'allowed_roles' => json_encode(['1']),
                'parent_menu' => '0'
            ],
            [
                'title' => 'Mailer',
                'icon' => '',
                'link' => '/page_mailer',
                'allowed_roles' => json_encode(['1']),
                'parent_menu' => 'Developer Option'

            ],
            [
                'title' => 'Menus',
                'icon' => '',
                'link' => '/page_menus',
                'allowed_roles' => json_encode(['1']),
                'parent_menu' => 'Developer Option'

            ]
        ];


        //first lets saparate parent menu from child menu
        $parent_menu = array_filter($menu_array, fn($menu) => $menu['parent_menu'] === '0');
        // dd($parent_menu);
        $child_menu = array_filter($menu_array, fn($menu) => $menu['parent_menu'] !== '0');


        //in this section we need to create empty array to map the parent menu

        $parentMap = [];

        //now lets insert first the parent to the database
        foreach ($parent_menu as $menu) {
            $parent = NavMenu::firstOrCreate([
                'title' => $menu['title'],
                'icon' => $menu['icon'],
                'link' => $menu['link'],
                'allowed_roles' => $menu['allowed_roles']
            ]);

            //lets save the id of the parent menu
            $parentMap[$menu['title']] = $parent->id;
        }

        //now lets insert the child menu
        foreach ($child_menu as $menu) {
            //first lets specify the parent menu title
            $parentMenuTitle = $menu['parent_menu'];

            //now lets get the id from the parentMap

            $parentId = $parentMap[$parentMenuTitle] ?? 0;

            //now lets check if the parent title is available on the parent map
            if ($parentId) {

                $parent = NavMenu::firstOrCreate([
                    'title' => $menu['title'],
                    'icon' => $menu['icon'],
                    'link' => $menu['link'],
                    'allowed_roles' => $menu['allowed_roles'],
                    'parent_menu' => $parentId
                ]);
            } else {
                //to handle the missing parent id
                echo "skipped {$menu['title']} parent is not found on the array";
            }
            dump($parentMap);
        }
    }
}
