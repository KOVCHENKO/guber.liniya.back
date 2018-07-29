<?php

namespace App\src\Services\Role\Entities;


class Cabinet
{
    public $id;
    public $name;
    public $icon;
    public $route;

    /**
     * Cabinet constructor.
     * @param $id
     * @param $name
     * @param $icon
     * @param $route
     */
    public function __construct($id, $name, $icon, $route)
    {
        $this->id = $id;
        $this->name = $name;
        $this->icon = $icon;
        $this->route = $route;
    }


}