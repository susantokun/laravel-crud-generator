<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

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

class Menu extends Model
{
    protected $table = 'admin_menus';
    protected $primaryKey = 'id';
    protected $fillable = ['parent_id', 'name', 'icon', 'url','status'];

    public function parent()
    {
        return $this->belongsTo(Menu::class);
    }
}
