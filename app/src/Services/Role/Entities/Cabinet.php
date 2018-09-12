<?php

namespace App\src\Services\Role\Entities;


class Cabinet
{
    public $id;
    public $name;
    public $routeName;
    public $route;

    /**
     * Cabinet constructor.
     * @param $id
     * @param $name
     * @param $routeName
     * @param $route
     */
    public function __construct($id, $name, $routeName, $route)
    {
        $this->id = $id;
        $this->name = $name;
        $this->routeName = $routeName;
        $this->route = $route;
    }


}