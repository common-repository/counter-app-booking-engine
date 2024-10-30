<?php

/*
Plugin Name: Counter.app Booking Engine
Description: Embed the Counter.App booking form quick and easy!
Author: Counter.App
Author URI: https://counter.app
Version: 1.1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

require __DIR__ . '/inc/plugin.class.php';
require __DIR__ . '/inc/widget.class.php';
require __DIR__ . '/inc/admin_page.class.php';

CounterBookingPlugin::register();
