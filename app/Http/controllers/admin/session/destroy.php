<?php

use Core\StaffAuthenticator;
use Core\Session;

if (Session::has('emp')){
    StaffAuthenticator::logout();
    redirect("/admin/login");
}