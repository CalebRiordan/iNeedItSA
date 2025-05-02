<?php

namespace Core\Repositories;

use Core\Container;
use Core\Database;

class BaseRepository{
	
    protected Database $db; 

    public function __construct() {
        $this->db = Container::resolve(Database::class);
    }
}