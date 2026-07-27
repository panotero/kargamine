<?php

namespace Database\Seeders;

use App\Models\NavMenu;
use Illuminate\Database\Seeder;
use RuntimeException;

class NavMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Top-level items use parent_title => null. Children resolve their
     * parent's actual row id by title lookup at seed time instead of a
     * hardcoded id literal - a hardcoded id drifts out of sync the
     * moment rows are added/removed/reordered through the live Menus
     * admin page, which is exactly what happened before this refactor
     * (parent_menu was hardcoded to '3'/'6', which stopped matching
     * "Developer Option"/"Settings" once the table changed). List each
     * parent before its children below; the lookup requires it.
     *
     * `icon` is a key into the nav_icons table (see NavIconSeeder), not a
     * FontAwesome class - that library was never actually loaded in this
     * app, so the old "fas fa-x" values here never rendered anything.
     */
    public function run(): void
    {
        $menu_array = [
            [
                'title' => 'Dashboard',
                'icon' => 'home',
                'link' => '/page_dashboard',
                'allowed_roles' => ['1'],
                'parent_title' => null,
                'menu_order' => '0',
            ],
            [
                'title' => 'CRM',
                'icon' => 'flag',
                'link' => '/page_crm',
                'allowed_roles' => ['1'],
                'parent_title' => null,
                'menu_order' => '2',
            ],
            [
                'title' => 'Clients',
                'icon' => 'building-office',
                'link' => 'page_clientMasters',
                'allowed_roles' => ['1', '2', '3', '4'],
                'parent_title' => null,
                'menu_order' => '4',
            ],
            [
                'title' => 'Contracts',
                'icon' => 'document-text',
                'link' => '/page_contracts',
                'allowed_roles' => ['1', '2', '3', '4'],
                'parent_title' => null,
                'menu_order' => '6',
            ],
            [
                'title' => 'Container Inventory',
                'icon' => 'cube',
                'link' => '/page_container_inventory',
                'allowed_roles' => ['1', '2', '3', '4'],
                'parent_title' => null,
                'menu_order' => '5',
            ],
            [
                'title' => 'Users',
                'icon' => 'users',
                'link' => '/page_usermanagement',
                'allowed_roles' => ['1'],
                'parent_title' => null,
                'menu_order' => '7',
            ],
            [
                'title' => 'Proposals',
                'icon' => 'paper-airplane',
                'link' => 'page_proposals',
                'allowed_roles' => ['1', '2', '3', '4'],
                'parent_title' => null,
                'menu_order' => '8',
            ],
            [
                'title' => 'Settings',
                'icon' => 'cog-6-tooth',
                'link' => '#',
                'allowed_roles' => ['1', '2', '3', '4'],
                'parent_title' => null,
                'menu_order' => '9',
            ],
            [
                'title' => 'Developer Option',
                'icon' => 'shield-check',
                'link' => '#',
                'allowed_roles' => ['1'],
                'parent_title' => null,
                'menu_order' => '10',
            ],

            // --- children: each parent above must be seeded first ---
            [
                'title' => 'Mailer',
                'icon' => 'envelope',
                'link' => '/page_mailer',
                'allowed_roles' => ['1'],
                'parent_title' => 'Developer Option',
                'menu_order' => '1',
            ],
            [
                'title' => 'Menus',
                'icon' => 'bars-3',
                'link' => '/page_menus',
                'allowed_roles' => ['1'],
                'parent_title' => 'Developer Option',
                'menu_order' => '2',
            ],
            [
                'title' => 'Theme',
                'icon' => 'squares-2x2',
                'link' => '/page_theme',
                'allowed_roles' => ['1'],
                'parent_title' => 'Developer Option',
                'menu_order' => '3',
            ],
            [
                'title' => 'Notification Test',
                'icon' => 'bell',
                'link' => '/page_notification_test',
                'allowed_roles' => ['1'],
                'parent_title' => 'Developer Option',
                'menu_order' => '4',
            ],
            [
                'title' => 'App Settings',
                'icon' => 'cog-6-tooth',
                'link' => '/page_maintenance',
                'allowed_roles' => ['1', '2', '3', '4'],
                'parent_title' => 'Settings',
                'menu_order' => '1',
            ],
            [
                'title' => 'Team Management',
                'icon' => 'briefcase',
                'link' => '/page_team_management',
                'allowed_roles' => ['1'],
                'parent_title' => 'Settings',
                'menu_order' => '2',
            ],
        ];

        foreach ($menu_array as $menu) {
            $parentId = '0';

            if ($menu['parent_title']) {
                $parent = NavMenu::where('title', $menu['parent_title'])->first();

                if (! $parent) {
                    throw new RuntimeException(
                        "NavMenuSeeder: parent '{$menu['parent_title']}' for '{$menu['title']}' must be listed (and seeded) before it."
                    );
                }

                $parentId = (string) $parent->id;
            }

            NavMenu::updateOrCreate(
                ['title' => $menu['title']],
                [
                    'icon' => $menu['icon'],
                    'link' => $menu['link'],
                    'allowed_roles' => json_encode($menu['allowed_roles']),
                    'parent_menu' => $parentId,
                    'menu_order' => $menu['menu_order'],
                ]
            );
        }
    }
}
