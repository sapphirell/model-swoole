<?php
require "vendor/autoload.php";
/**
 *  私用swoole面向对象开发框架
 *  Proword by Sapphirell. 2018.5
 */
use App\Server\Route;

$App = new Route();
$App->start_ws();

