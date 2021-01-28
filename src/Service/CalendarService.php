<?php
declare(strict_types=1);

namespace App\Service;


use App\Entity\Event;
use DateTime;
use Exception;
use Google_Service_Calendar_Event;

class CalendarService
{

    public function getEventEntity(Google_Service_Calendar_Event $event, string $id) :?Event
    {
     $start = $event->getStart()->getDateTime() !== null
         ?
         $event->getStart()->getDateTime()
         :
         $event->getStart()->getDate();
     $end = $event->getEnd()->getDateTime() !== null
         ?
         $event->getEnd()->getDateTime()
         :
         $event->getEnd()->getDate();

        try {
            return new Event(
                $event->getSummary(),
                $id,
                new DateTime($start),
                new DateTime($end)
            );
        } catch (Exception $e) {
        }
        return null;
    }
}
