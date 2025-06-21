<?php

use Core\Session;

view("seller/create", ['errors' => Session::get('errors')]);