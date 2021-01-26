<?php
declare(strict_types=1);

namespace App\Controller;



use App\Service\AuthService;
use Google_Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @Route("/", name="index_route")
     */
    public function index()
    {
        $client = $this->authService->tryAuthenticate();
        $availableCalendars = $this->authService->getCalendars($client);
        return $this->render('index.html.twig',['availableCalendars'=>$availableCalendars]);
    }

    /**
     * @Route("/oauth", name="authorize")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function authorize(Request $request)
    {
        if (($code = $request->get('code'))!==null) {
            $this->authService->authWithCode($code);
            return new RedirectResponse('/');
        } else {
            if(($result = $this->authService->tryAuthenticate())->getAccessToken()==null){
                return new RedirectResponse($result->createAuthUrl());
            }else{
                return new RedirectResponse('/');
            }
        }
    }
}
