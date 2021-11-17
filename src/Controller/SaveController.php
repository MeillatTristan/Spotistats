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
      $songsArray = ['short' => ['items' => []], 'mid' => ['items' => []], 'long' => ['items' => []]];
      $tops = ['short' => [], 'mid' => [], 'long' => []];
      foreach ($songs as $song) {
        if($song->getTimeRange() == 'short'){
          $songsArray['short']['items'][] = $song->getSpotifyId();
        }
        elseif($song->getTimeRange() == 'mid'){
          $songsArray['mid']['items'][] = $song->getSpotifyId();
        }
        elseif($song->getTimeRange() == 'long'){
          $songsArray['long']['items'][] = $song->getSpotifyId();
        }
      }

      $short = $this->api->getTracks($songsArray['short']['items']);
      $mid = $this->api->getTracks($songsArray['mid']['items']);
      $long = $this->api->getTracks($songsArray['long']['items']);
      
      foreach ($short as $track) {
        $tops['short']['items'] = $track;
      }
      foreach ($mid as $track) {
        $tops['mid']['items'] = $track;
      }
      foreach ($long as $track) {
        $tops['long']['items'] = $track;
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
      $songsArray = ['short' => ['items' => []], 'mid' => ['items' => []], 'long' => ['items' => []]];
      $tops = ['short' => [], 'mid' => [], 'long' => []];
      foreach ($songs as $song) {
        if($song->getTimeRange() == 'short'){
          $songsArray['short']['items'][] = $song->getSpotifyId();
        }
        elseif($song->getTimeRange() == 'mid'){
          $songsArray['mid']['items'][] = $song->getSpotifyId();
        }
        elseif($song->getTimeRange() == 'long'){
          $songsArray['long']['items'][] = $song->getSpotifyId();
        }
      }

      $short = $this->api->getArtists($songsArray['short']['items']);
      $mid = $this->api->getArtists($songsArray['mid']['items']);
      $long = $this->api->getArtists($songsArray['long']['items']);
      
      foreach ($short as $track) {
        $tops['short']['items'] = $track;
      }
      foreach ($mid as $track) {
        $tops['mid']['items'] = $track;
      }
      foreach ($long as $track) {
        $tops['long']['items'] = $track;
      }

      return $this->render('top/artists.html.twig',[
        'topTracks' => $tops,
        'createdAt' => $save->getCreatedAt()
      ]);
    }
}