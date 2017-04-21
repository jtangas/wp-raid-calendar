<?php
/**
 * Created by PhpStorm.
 * User: justintangas
 * Date: 4/11/17
 * Time: 12:42 PM
 */

namespace RaidCalendar;


use RaidCalendar\Service\AdminCalendarService;
use RaidCalendar\Service\AdminEventTypeService;

class Admin
{
    /** @var AdminEventTypeService $eventTypeService */
    protected $eventTypeService;
    /** @var AdminCalendarService $calendarService */
    protected $calendarService;

    public function __construct($calendarService, $eventTypeService)
    {
        $this->calendarService = $calendarService;
        $this->eventTypeService = $eventTypeService;
    }

    public function setupMenu()
    {
        add_action('admin_enqueue_scripts', [$this, 'loadScripts']);
        add_action('admin_init', [$this, 'loadStyles']);
        add_menu_page('Raid Calendar', 'Raid Calendar', 'manage_options', 'raid-calendar', [$this, 'init']);
    }

    public function loadScripts()
    {
        wp_register_script('jquery_ui', 'https://code.jquery.com/ui/1.12.0/jquery-ui.min.js', ['jquery'], null, true);
        wp_register_script('tabs', plugin_dir_url(__FILE__) . 'js/tabs.js', ['jquery'], null, true);
        wp_register_script('tooltips', plugin_dir_url(__FILE__) . 'js/tooltip.js', ['jquery'], null, true);
        wp_enqueue_script(['jquery_ui', 'tabs', 'tooltips']);
    }

    public function loadStyles()
    {
        wp_register_style('jquery_ui_vader', 'https://code.jquery.com/ui/1.12.1/themes/black-tie/jquery-ui.css');
        wp_register_style('raid_calendar', plugin_dir_url(__FILE__) . 'css/style.css');
        wp_enqueue_style(['jquery_ui_vader', 'raid_calendar']);
    }

    public function init()
    {
        $events = $this->calendarService->getEvents();
        $calendar = $this->calendarService->generateCalendar();
        //$eventTypes = $this->eventTypeService->getEventTypes();
        include 'Views/AdminTemplate.php';
    }
}