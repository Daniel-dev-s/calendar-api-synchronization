<?php
declare(strict_types=1);

namespace App\Service;


use Exception;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Calendar;
use Google_Service_Calendar_CalendarList;
use Google_Service_Calendar_Event;

class GoogleApiService
{
    private string $tokenFolder = '../token/';
    private string $tokenFile = 'token.json';
    private string $tokenPath = '';
    private string $redirect_uri = 'oauth';
    private string $configPath = '../client_id_2.json';
    public function __construct()
    {
        $this->redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $this->redirect_uri;
        $this->tokenPath =$this->tokenFolder.'/'.$this->tokenFile;
    }

    public function tryAuthenticate() :?Google_Client
    {
        $client = $this->getBaseClient();
        try {
            $client->setAccessType('offline');        // offline access
            $client->setIncludeGrantedScopes(true);   // incremental auth
        } catch (Exception $e) {
            print $e;
        }
        if(file_exists($this->tokenPath)){
            $accessToken = json_decode(file_get_contents($this->tokenPath), true);
            $client->setAccessToken($accessToken);
        }
        if($client->isAccessTokenExpired()){
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            }
            }
        return $client;
    }
    public function authWithCode($code) :?Google_Client
    {
        $client = $this->getBaseClient();
        $accessToken = $client->fetchAccessTokenWithAuthCode($code);
        $client->setAccessToken($accessToken);
        if(!file_exists($this->tokenPath)){
            mkdir($this->tokenFolder, 0700, true);
        }
        file_put_contents($this->tokenPath, json_encode($client->getAccessToken()));
        return $client;
    }
    public function getBaseClient(): Google_Client
    {
        $client = new Google_Client();
        try {
            $client->setAuthConfig($this->configPath);
            $client->addScope(Google_Service_Calendar::CALENDAR);
            $client->setRedirectUri($this->redirect_uri);
        } catch (Exception $e) {
        }
        return $client;
    }
    public function getAvailableCalendars(Google_Client $client,
                                          array $persistedCalendars): array
    {
        $service = new Google_Service_Calendar($client);
        $all = $service->calendarList->listCalendarList();
        $available = [];
        foreach($all as $gettedCalendar){
            $canAdd = true;
            foreach($persistedCalendars as $pCalendar){
                if($pCalendar->getGoogleId() === $gettedCalendar->getId())
                {
                    $canAdd = false;
                }
            }
            if($canAdd)array_push($available,$gettedCalendar);
        }
        return $available;
    }

    public function getCalendarById(string $id) : Google_Service_Calendar_Calendar
    {
        $calendar = null;
        if(($client = $this->tryAuthenticate())!==null) {
            $service = new Google_Service_Calendar($client);
            $calendar = $service->calendars->get($id);
        }
        return $calendar;
    }

    public function getEvents(string $id) :?array
    {
        $events = null;
        if(($client = $this->tryAuthenticate())!==null) {
            $service = new Google_Service_Calendar($client);
            $events = $service->events->listEvents($id)->getItems();
        }
        return $events;
    }
}
