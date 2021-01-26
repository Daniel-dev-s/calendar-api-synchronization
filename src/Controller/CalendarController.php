<?php


namespace App\Controller;


use App\Entity\Calendar;
use App\Service\CalendarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    private CalendarService $calendarService;

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * @Route("/add_calendar", name="add_calendar")
     */
    public function addCalendar(Request $request) :void
    {
       if($id = $request->get('id')){
           $calendar = $this->getDoctrine()->getRepository(Calendar::class)->findBy(["googleId"=>$id]);
            if($calendar==null){
                $calendar = new Calendar();// get from authservice object and save it

            }
       }
    }
}
