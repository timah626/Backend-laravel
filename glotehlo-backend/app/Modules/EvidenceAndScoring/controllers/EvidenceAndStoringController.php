<?php


namespace App\Modules\EvidenceAndStoring\controllers;

use  App\Http\controllers\Controller;
use App\Modules\EvidenceAndScoring\services\EvidenceAndScoringService;



use Illuminate\Http\Request;

class EvidenceAndStoringController extends Controller{

public function __construct (
    private EvidenceAndScoringService $evidenceAndScoringService
) {

}

 /*public function  checkDevice( Request $request) {
 
   $deviceToken = $request -> deviceToken;

   $user = $request -> user();

   $device = $this -> evidenceAndScoringService -> checkDevice ($user,  $deviceToken);

 }*/

}





