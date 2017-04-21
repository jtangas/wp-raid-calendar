<?php
/**
 * Created by PhpStorm.
 * User: justintangas
 * Date: 4/11/17
 * Time: 12:39 PM
 *
 * Plugin Name: Raid Calendar
 * Description: A Raid Calendar Plugin for Wordpress
 * Author: Justin Tangas
 * Version: 0.1
 */
require 'src/Admin.php';
require 'src/Service/AdminCalendarService.php';
require 'src/Service/AdminEventTypeService.php';
require 'src/Service/SignupService.php';

global $wpdb;

$calendarService = new RaidCalendar\Service\AdminCalendarService($wpdb);
$eventTypeService = new RaidCalendar\Service\AdminEventTypeService($wpdb);
$signUpService = new RaidCalendar\Service\SignupService($wpdb);
$admin = new RaidCalendar\Admin($calendarService, $eventTypeService, $signUpService);

add_action('admin_menu', [$admin, 'setupMenu']);