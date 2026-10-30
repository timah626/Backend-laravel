<?php

namespace App\Modules\OfficeNetworks\Controllers;

use  App\Http\Controllers\Controller;



use App\Modules\OfficeNetworks\Services\OfficeNetworkService;


use Illuminate\Http\Request;




class OfficeNetworkController extends Controller {


public function __construct (

private OfficeNetworkService $officeNetworkService,



) {}


public function addCurrentConnection (Request $request   , string $siteId)

{



    $request->validate([
        'label' => ['required', 'string'],

    ]);

    $networks = $this -> officeNetworkService->addCurrentConnection (

        $user = $request -> user (),
        $ip =  $request -> ip(),
        $label = $request->input('label'),
        $siteId
        );



  return (response () -> json ([
        'message' => 'nework added successfully ',
        'data' => $networks,
    ], 201));


}



public function getAllNetworkBySite (Request $request , string $siteId) {


   $networks = $this->officeNetworkService->getAllNetworkBySite(
        $request->user(),
        $siteId,
    );


    return response()->json(['data' => $networks], 200);





}












}


