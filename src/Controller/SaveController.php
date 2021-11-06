<?php

namespace App\Controller;

use App\Entity\Save;
use App\Repository\SaveRepository;
use App\Repository\SongRepository;
use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SaveController extends AbstractController
{

  private $api;

  public function __construct(SpotifyWebAPI $api)
  {
      $this->api = $api;
  }
    /**
     * @Route("/saves", name="saves")
     */
    public function returnSaves(SaveRepository $saveRepository): Response
    {
      $savesArtists = $saveRepository->findBy(["user" => $this->getUser(), "type" => "artists"], ['id'=> "DESC"]);
      $savesTracks = $saveRepository->findBy(["user" => $this->getUser(), "type" => "tracks"], ['id'=> "DESC"]);
      return $this->render('saves/saves.html.twig',[
        'savesArtists' => $savesArtists,
        'savesTracks' => $savesTracks
      ]);
    }

    /**
     * @Route("/saves/tracks/{id}", name="savesTracks")
     */
    public function showSavesTracks(Save $save){
      $songs = $save->getSongs();
      $tops = ['short' => ['items' => []], 'mid' => ['items' => []], 'long' => ['items' => []]];
      foreach ($songs as $song) {
        $songSpotify = $this->api->getTrack($song->getSpotifyId());
        if($song->getTimeRange() == 'short'){
          $tops['short']['items'][] = $songSpotify;
        }
        elseif($song->getTimeRange() == 'mid'){
          $tops['mid']['items'][] = $songSpotify;
        }
        elseif($song->getTimeRange() == 'long'){
          $tops['long']['items'][] = $songSpotify;
        }
      }

      return $this->render('top/tracks.html.twig',[
        'topTracks' => $tops,
        'createdAt' => $save->getCreatedAt()
      ]);
    }

    /**
     * @Route("/saves/artists/{id}", name="savesArtists")
     */
    public function showSavesArtists(Save $save){
      $songs = $save->getSongs();
      $tops = ['short' => ['items' => []], 'mid' => ['items' => []], 'long' => ['items' => []]];
      foreach ($songs as $song) {
        $songSpotify = $this->api->getArtist($song->getSpotifyId());
        if($song->getTimeRange() == 'short'){
          $tops['short']['items'][] = $songSpotify;
        }
        elseif($song->getTimeRange() == 'mid'){
          $tops['mid']['items'][] = $songSpotify;
        }
        elseif($song->getTimeRange() == 'long'){
          $tops['long']['items'][] = $songSpotify;
        }
      }

      return $this->render('top/artists.html.twig',[
        'topTracks' => $tops,
        'createdAt' => $save->getCreatedAt()
      ]);
    }
}