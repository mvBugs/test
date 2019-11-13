<?php

use Illuminate\Database\Eloquent\Model;

class AdminMenuTableSeeder extends MenuBaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->seedMenu($this->getData());

        $this->command->info(__CLASS__ . ' success!');
    }

    public function getData(): array
    {
        return [

            [
                'name' => 'Админ. меню',
                'system_name' => 'admin_menu',
                'data' => [
                    'has_hierarchy' => 1,
                ],
                'safe' => true,
                'children' => [
                    [
                        'name' => 'Меню',
                        'path' => '',
                        'data' => [
                            'icon' => '',
                            'header' => 1,
                        ],
                    ],

                    [
                        'name' => 'Головна',
                        'path' => 'admin',
                        'data' => [
                            'icon' => 'fa fa-dashboard',
                            'pattern_url' => '\S*admin\/?((\?{1}\S*)|$)',
                        ],
                    ],
                    [
                        'name' => 'Points',
                        'path' => 'admin/points',
                        'data' => [
                            'icon' => 'fa fa-users',
                            'pattern_url' => '\S*admin\/?((\?{1}\S*)|$)',
                        ],
                    ],
                ],
            ],
        ];
    }
}
