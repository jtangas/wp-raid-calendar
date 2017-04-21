<?php
    $timezone = new \DateTimeZone('America/Los_Angeles');
?>
<h1>Raid Calendar</h1>
<div id="tabs">
    <ul>
        <li><a href="#calendar">Calendar</a></li>
        <li><a href="#events">Events</a></li>
        <li><a href="#create">Create Event</a></li>
    </ul>

    <div id="calendar">
        <?php
        $currentDate = (new \DateTime('now', $timezone))->format('Y-m-d');
        $currentMonthYear = 0;
        $output = '';
        $first = null;
        foreach ($calendar as $day) {
            $isDay = ($day['date']->format('Y-m-d') == $currentDate) ? 'currentDate' : '';

            if ($day['date']->format('Ym') !== $currentMonthYear) {
                $first = true;
                $currentMonthYear = $day['date']->format('Ym');
                $output .= "<table class=\"raid_calendar\">";
                $output .= "<thead><tr><th colspan=\"7\"><strong>" . $day['date']->format('F Y') . "</strong></th></tr>";
                $output .= "<tr>
                                <th class=\"day\">Sunday</th>
                                <th class=\"day\">Monday</th>
                                <th class=\"day\">Tuesday</th>
                                <th class=\"day\">Wednesday</th>
                                <th class=\"day\">Thursday</th>
                                <th class=\"day\">Friday</th>
                                <th class=\"day\">Saturday</th>
                            </tr>";
                $output .= "</thead>";
            }

            if ($first) {
                $output .= "<tr>";
                $pad = $day['date']->format('w');
                for ($i = 0; $i < $pad; $i++) {
                    $output .= "<td class=\"day\"><span></span></td>";
                }
                $first = false;
            }

            $output .= "<td class=\"" . $isDay . "\"><span>" . $day['date']->format('j') . "</span><br>";
            $raidMembers = [
                'tanks' => [],
                'heals' => [],
                'dps' => [],
            ];

            $raidStatus = ['maybe', 'yes', 'no'];

            for ($i = 0; $i <= 55; $i++) {
                $roles = ['dps'];
                if (count($raidMembers['tanks']) < 6) {
                    $roles[] = 'tanks';
                }

                if (count($raidMembers['heals']) < 8) {
                    $roles[] = 'heals';
                }

                $role = array_rand($roles);
                $raidMembers[$roles[$role]][] = [
                    'person' => 'Justin',
                    'status' => $raidStatus[array_rand($raidStatus)]
                ];

            }


            if (count($day['events'])) {
                foreach ($day['events'] as $event) {
                    $output .= "<span data-raid='" . json_encode($raidMembers) . "'>" . $event->event_name ."</span><br>";
                }
            }
            $output .= "</td>";

            if ($day['date']->format('w') == 6) {
                $output .= "</tr>";
            }

            if ($day['date']->format('d') == $day['date']->format('t')) {
                $output .= "</table>";
            }
        }

        echo $output;
        ?>
    </div>
    <div id="events">
        <table width="100%">
            <thead>
                <tr>
                    <th align="left">Instance Name</th>
                    <th align="left">Time</th>
                    <th align="left">Description</th>
                    <th align="left">Start Date</th>
                    <th align="left">End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($events)) {
                    foreach ($events as $event) {
                        include 'Event.php';
                    }
                } else {
                    ?>
                    <tr colspan="5"><p>No Events Found</p></tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div id="create">
        <h1>You thought you could create</h1>
    </div>
</div>
