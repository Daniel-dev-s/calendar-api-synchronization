<?php
declare(strict_types=1);

namespace App\Controller;


use App\Entity\Calendar;
use App\Service\GoogleApiService;
use Google_Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private GoogleApiService $googleApiService;

    public function __construct(GoogleApiService $googleApiService)
    {
        $this->googleApiService = $googleApiService;
    }

    /**
     * @Route("/", name="index_route")
     */
    public function index()
    {
        $persistedCalendars = $this->getDoctrine()->getRepository(Calendar::class)->findAll();
        $client = $this->googleApiService->tryAuthenticate();
        $availableCalendars = $this->googleApiService->getAvailableCalendars($client,$persistedCalendars);
        return $this->render('index.html.twig',
            ['availableCalendars' => $availableCalendars, 'persistedCalendars' => $persistedCalendars]);
    }

    /**
     * @Route("/oauth", name="authorize")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function authorize(Request $request)
    {
        if (($code = $request->get('code')) !== null) {
            $this->googleApiService->authWithCode($code);
            return new RedirectResponse('/');
        } else {
            if (($result = $this->googleApiService->tryAuthenticate())->getAccessToken() == null) {
                return new RedirectResponse($result->createAuthUrl());
            } else {
                return new RedirectResponse('/');
            }
        }
    }
}
