<?php
declare(strict_types=1);

namespace App\Controller;


use App\Entity\Calendar;
use App\Entity\Event;
use App\Service\GoogleApiService;
use App\Service\CalendarService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    private CalendarService $calendarService;
    private GoogleApiService $googleApiService;

    public function __construct(CalendarService $calendarService, GoogleApiService $googleApiService)
    {
        $this->calendarService = $calendarService;
        $this->googleApiService = $googleApiService;
    }

    /**
     * @Route("/add_calendar", name="add_calendar", methods={"POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function addCalendar(Request $request): Response
    {
        $manager = $this->getDoctrine()->getManager();
        if ($id = $request->get('id')) {
            $calendar = $this->getDoctrine()
                ->getRepository(Calendar::class)
                ->findBy(["googleId" => $id]);
            if ($calendar == null) {
                $remoteCalendar = $this->googleApiService->getCalendarById($id);
                $calendar = new Calendar(
                    $remoteCalendar->getSummary(),
                    $id,
                    new DateTime()
                );
                $events = $this->googleApiService->getEvents($id);

                foreach ($events as $event) {
                    $event_to_add = $this->calendarService->getEventEntity($event,$id);
                    $manager->persist($event_to_add);
                }
                $manager->persist($calendar);
                $manager->flush();
            }
        }
        return new Response();
    }

    /**
     * @Route("/getEvents", name="get_events", methods={"POST","GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getEvents(Request $request): JsonResponse
    {
        $query = null;
        if (($id = $request->get("id")) !== null) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery('SELECT c FROM App\Entity\Event c where c.calendarId = :id')
                ->setParameter('id', $id);
        }
        return new JsonResponse($query->getArrayResult());
    }

}
