<?php

namespace App\Controller;

use App\Entity\Save;
use App\Entity\Song;
use App\Repository\SaveRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIAuthException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class TopController extends AbstractController
{

    private $api;
    private $session;

    public function __construct(SpotifyWebAPI $api, Session $session, RequestStack $requestStack)
    {
        $this->api = $api;
        $this->sessionSpotify = $session;
        $this->session = $requestStack->getSession();
    }

    /**
     * @Route("/tracks", name="tracks")
     */
    public function tracks(EntityManagerInterface $manager, SaveRepository $saveRepository){
        if(!$this->session->get('callback')){
            $this->session->set('type', 'tracks');
            return $this->redirectToRoute('callback');
        }
        $tops = $this->session->get('callback');
        $this->session->remove('callback');

        $savesUser = $saveRepository->findBy(["user" => $this->getUser()]);
        $dateNow = date_format(new DateTime(), 'Y-m-d');
        foreach ($savesUser as $key => $saveUser) {
            $dateSave = date_format($saveUser->getCreatedAt(), 'Y-m-d');
            if($dateNow == $dateSave && $saveUser->getType() == 'tracks'){
                return $this->render('top/tracks.html.twig',[
                    "topTracks" => $tops
                ]);
            }
        }
        $save = new Save();
        $user = $this->getUser();
        $save->setUser($user);
        $save->setType('tracks');
        foreach ($tops as $timeRange => $top) {
            foreach ($top->items as $index => $song) {
                $idSong = $song->id;
                
                $ranking = $index + 1;
                $song = new Song();
                $song->setSave($save);
                $song->setTimeRange($timeRange);
                $song->setSpotifyId($idSong);
                $song->setRanking($ranking);
                $manager->persist($song);
            }
        }
        $manager->flush();
        return $this->render('top/tracks.html.twig',[
            "topTracks" => $tops
        ]);
    }

    /**
     * @Route("/artists", name="artists")
     */
    public function artists(EntityManagerInterface $manager, SaveRepository $saveRepository){
        if(!$this->session->get('callback')){
            $this->session->set('type', 'artists');
            return $this->redirectToRoute('callback');
        }
        $tops = $this->session->get('callback');
        $this->session->remove('callback');

        $savesUser = $saveRepository->findBy(["user" => $this->getUser()]);
        $dateNow = date_format(new DateTime(), 'Y-m-d');
        foreach ($savesUser as $key => $saveUser) {
            $dateSave = date_format($saveUser->getCreatedAt(), 'Y-m-d');
            if($dateNow == $dateSave && $saveUser->getType() == 'artists'){
                return $this->render('top/artists.html.twig',[
                    "topTracks" => $tops
                ]);
            }
        }
        $save = new Save();
        $user = $this->getUser();
        $save->setUser($user);
        $save->setType('artists');
        foreach ($tops as $timeRange => $top) {
            foreach ($top->items as $index => $song) {
                $idSong = $song->id;
                
                $ranking = $index + 1;
                $song = new Song();
                $song->setSave($save);
                $song->setTimeRange($timeRange);
                $song->setSpotifyId($idSong);
                $song->setRanking($ranking);
                $manager->persist($song);
            }
        }
        $manager->flush();
        return $this->render('top/artists.html.twig',[
            "topTracks" => $tops
        ]);
    }

    /**
    * @Route("/callback/", name="callback")
    */
    public function callbackFromSpotify(Request $request): Response
    {
        $type = $this->session->get('type');
        try {
            $this->sessionSpotify->requestAccessToken($request->query->get('code'));
        } catch (SpotifyWebAPIAuthException $e) {
            return $this->redirectToRoute('some_redirect');
        }

        $this->api->setAccessToken($this->sessionSpotify->getAccessToken());
        $short = $this->api->getMyTop($type, ["limit" => 50, "offset" => 0, "time_range" => "short_term"]);
        $mid = $this->api->getMyTop($type, ["limit" => 50, "offset" => 0, "time_range" => "medium_term"]);
        $long = $this->api->getMyTop($type, ["limit" => 50, "offset" => 0, "time_range" => "long_term"]);
        $callback = ["short" => $short, "mid" => $mid, "long" =>$long];
        $this->session->set('callback', $callback);
        return $this->redirectToRoute("$type");

        
    }

    /**
     * @Route("/redirect", name="some_redirect")
     */
    public function redirectToSpotify(): Response
    {
        $options = [
            'scope' => [
                'user-read-email',
                'user-top-read'
            ],
        ];

        return $this->redirect($this->sessionSpotify->getAuthorizeUrl($options));
    }
}
