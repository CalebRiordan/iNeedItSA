<?php

use Core\Session;

view('admin/session/create', ['errors' => Session::get('errors')]);