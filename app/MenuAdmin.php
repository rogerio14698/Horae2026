<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class MenuAdmin extends Model
{
    protected $table = 'menu_admin';

    // Recursive function that builds the menu from an array or object of items
    // In a perfect world some parts of this function would be in a custom Macro or a View
    public function buildMenu($menu, $parentid = 0)
    {
        $result = null;
        foreach ($menu as $item)
            if ($item->parent_id == $parentid) {
                $result .= "<li class='dd-item nested-list-item' data-order='{$item->order}' data-id='{$item->id}'>
          <div class='dd-handle nested-list-handle'>
            <span class='glyphicon glyphicon-move'></span>
          </div>
          <div class='nested-list-content".($item->separator=='1'?' separator':'')."'>";
            if ($item->icon != ''){
                $result .= "<i class=\"fa fa-".$item->icon."\"></i> ";
            }

          $result .= "{$item->label}
            <div class='pull-right'>";
        if( \Auth::user()->compruebaSeguridad('editar-elemento-menu-admin') == true)
          $result .= "<a href='" . url("eunomia/menu_admin/edit/{$item->id}") . "' class='btn btn-warning btn-xs me-1'><i class='fas fa-edit'></i> Editar</a>";
        if( \Auth::user()->compruebaSeguridad('eliminar-elemento-menu-admin') == true)
          $result .= "<button class='delete_toggle btn btn-danger btn-xs' style='margin-left:.5em;' rel='{$item->id}'><i class='fas fa-trash'></i> Eliminar</button>";
            $result .= "</div>
          </div>" . $this->buildMenu($menu, $item->id) . "</li>";
        }
        return $result ? "\n<ol class=\"dd-list\">\n$result</ol>\n" : null;
    }

    // Getter for the HTML menu builder
    public function getHTML($items)
    {
        return $this->buildMenu($items);
    }

}
