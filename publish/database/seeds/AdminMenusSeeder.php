<?php

use Illuminate\Database\Seeder;

/*
 * |==============================================================|
 * | Please DO NOT modify this information :                      |
 * |--------------------------------------------------------------|
 * | Author          : Susantokun
 * | Email           : admin@susantokun.com
 * | Instagram       : @susantokun
 * | Website         : http://www.susantokun.com
 * | Youtube         : http://youtube.com/susantokun
 * | File Created    : Friday, 28th February 2020 2:50:20 pm
 * | Last Modified   : Friday, 28th February 2020 2:50:20 pm
 * |==============================================================|
 */

class AdminMenusSeeder extends Seeder
{
    public function run()
    {
        DB::table('admin_menus')->insert(
            [
                [
                    'parent_id'  => '0',
                    'name'       => 'Dashboard',
                    'icon'       => 'fas fa-tachometer-alt',
                    'url'        => 'home',
                    'status'     => 'enable',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'parent_id'  => '0',
                    'name'       => 'Manage Admin',
                    'icon'       => 'fas fa-tasks',
                    'url'        => 'admin',
                    'status'     => 'enable',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'parent_id'  => '2',
                    'name'       => 'Menu',
                    'icon'       => 'fas fa-caret-right',
                    'url'        => 'admin/menus',
                    'status'     => 'enable',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            ]
        );
    }
}
