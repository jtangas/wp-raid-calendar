<tr>
    <td><?php echo $event->event_name; ?></td>
    <td><?php echo $event->event_time; ?></td>
    <td><?php echo $event->event_description; ?></td>
    <td><?php echo (new \DateTime($event->event_frequency_start, $timezone))->format('D M jS, Y'); ?></td>
    <td><?php echo (new \DateTime($event->event_frequency_end, $timezone))->format('D M jS, Y'); ?></td>
</tr>
<tr class="hidden">
    <td colspan="1">
        <strong>Tanks</strong><br>
    </td>
    <td colspan="1">
        <strong>Healers</strong><br>
    </td>
    <td colspan="3">
        <strong>DPS</strong><br>
    </td>
</tr>