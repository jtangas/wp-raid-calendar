<?php
/**
 * Created by PhpStorm.
 * User: justintangas
 * Date: 4/11/17
 * Time: 12:45 PM
 */

namespace RaidCalendar\Service;


class AdminCalendarService
{
    const VERSION = 105;
    const ID_FIELD = 'event_id';
    const NAME_FIELD = 'event_name';
    const TIME_FIELD = 'event_time';
    const DESC_FIELD = 'event_description';
    const FREQ_START = 'event_frequency_start';
    const FREQ_END = 'event_frequency_end';
    const EVENT_ID_FIELD = 'event_id';
    const EVENT_DATEFIELD = 'event_datetime';
    const EVENT_EVENT_FIELD = 'event_calendar_id';
    const SIGNUP_EVENT_FIELD = 'event_id';
    const SIGNUP_USER_FIELD = 'wordpress_user';
    const SIGNUP_CHARACTER = 'character_name';
    const SIGNUP_ROLE = 'role';

    protected $db;
    protected $tableName;

    public function __construct($wpdb)
    {
        $this->db = $wpdb;
        $this->tableName = $wpdb->prefix . 'raid_calendar';
        $this->eventTable = $wpdb->prefix . 'raid_events';
        $this->eventRegistrationTable = $wpdb->prefix . 'raid_signups';

        $version = get_option('raid_calendar_version');

        if ($version !== self::VERSION) {
            $sql = 'CREATE TABLE ' . $this->tableName . ' (
                ' . self::ID_FIELD . ' mediumint(9) NOT NULL AUTO_INCREMENT,
                ' . self::TIME_FIELD . ' time NOT NULL,
                ' . self::NAME_FIELD . ' tinytext NOT NULL,
                ' . self::DESC_FIELD . ' text,
                ' . self::FREQ_START . ' datetime NOT NULL,
                ' . self::FREQ_END . ' datetime NOT NULL,
                PRIMARY KEY  ('. self::ID_FIELD .')
            );';

            $eventSql = 'CREATE TABLE ' . $this->eventTable . ' (
                ' . self::EVENT_ID_FIELD . ' mediumint(9) NOT NULL AUTO_INCREMENT,
                ' . self::EVENT_DATEFIELD . ' datetime NOT NULL,
                ' . self::EVENT_EVENT_FIELD . ' mediumint(9) NOT NULL,
                KEY event (' . self::EVENT_ID_FIELD . ')
            );';

            $signUpsSql = 'CREATE TABLE ' . $this->eventRegistrationTable . ' (
                ' . self::SIGNUP_EVENT_FIELD . ' mediumint(9) NOT NULL,
                ' . self::SIGNUP_USER_FIELD . ' mediumint(9) NOT NULL,
                ' . self::SIGNUP_CHARACTER . ' varchar(64) NOT NULL,
                ' . self::SIGNUP_ROLE . ' enum("dps", "heals", "tank"),
                KEY registered (' . self::SIGNUP_EVENT_FIELD . ',' . self::SIGNUP_USER_FIELD . ',' . self::SIGNUP_CHARACTER . ')
            );';

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            dbDelta($eventSql);
            dbDelta($signUpsSql);

            update_option('raid_calendar_version', self::VERSION);
        }
    }

    public function generateCalendar()
    {
        $timeZone = new \DateTimeZone('America/Los_Angeles');
        $startOfMonth = new \DateTime('first day of this month', $timeZone);
        $endOfNextMonth = new \DateTime("last day of next month", $timeZone);
        $scheduledEvents = $this->getScheduledEvents($startOfMonth, $endOfNextMonth);
        $currentDate = clone $startOfMonth;

        $calendar = [];
        while ($currentDate->getTimestamp() <= $endOfNextMonth->getTimestamp()) {
            $activeDate = clone $currentDate;
            $day = [
                'date' => $activeDate,
                'events' => []
            ];
            foreach ($scheduledEvents as $event) {
                $eventDate = new \DateTime($event->event_datetime, $timeZone);
                if ($eventDate->format('Y-m-d') == $activeDate->format('Y-m-d')) {
                    $day['events'][] = $event;
                }
            }

            $calendar[] = $day;
            $currentDate->modify("+1 day");
        }

        return $calendar;
    }

    public function getEvents()
    {
        $results = $this->db->get_results('SELECT * FROM ' . $this->tableName);
        return $results;
    }

    public function getEventAttendees($eventId)
    {
        $results = $this->db->get_results('SELECT * FROM ' . $this->eventRegistrationTable . ' WHERE ' . self::SIGNUP_EVENT_FIELD . ' = ' . $eventId);
        return $results;
    }

    public function createEvent()
    {

    }

    public function deleteEvent()
    {

    }

    public function editEvent()
    {

    }

    private function getScheduledEvents($startOfMonth, $endOfNextMonth)
    {
        $query = 'SELECT t.' . self::NAME_FIELD . ',' .
            't.' . self::DESC_FIELD . ',' .
            'e.' . self::EVENT_DATEFIELD . ',' .
            'e.' . self::EVENT_ID_FIELD .
            ' FROM ' . $this->eventTable . ' e' .
            ' LEFT JOIN ' . $this->tableName .
            ' t ON t.' . self::ID_FIELD . ' = e.' . self::EVENT_EVENT_FIELD .
            ' WHERE e.' . self::EVENT_DATEFIELD . ' BETWEEN "' .
            $startOfMonth->format('Y-m-01 00:00:00') . '" AND "' . $endOfNextMonth->format('Y-m-t 23:59:59') . '"';

        $results = $this->db->get_results($query);

        return $results;
    }
}