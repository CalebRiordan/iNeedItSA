<?php

namespace Core;

class BaseRepository{
	
    protected Database $db; 

    public function __construct() {
        $this->db = Container::resolve(Database::class);
    }
}