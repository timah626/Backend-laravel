<?php


namespace  App\Modules\Organisation\controllers ;

use  App\Http\controllers\Controller;

use App\Modules\Organisation\services\OrganisationService;

use Illuminate\Http\Request;


class OrganisationController extends Controller {

 public function __construct (
    private OrganisationService $organisationService,
 ) {}





 public function createSites ( Request $request   ) {

  $site = $this->organisationService->createSites(




        $request-> all(),
        $request->user(),

    );

    return (response () -> json ([
        'message' => 'sites created successfully',
        'data' => $site,
    ], 201));


 }

 public function createDepartments ( Request $request,   string $siteId  ) {



  $validatedData = $request -> validate([

      'name' => 'required',

    ]);




  $department = $this->organisationService->createDepartment(
    $request->user(),
    $validatedData,
    $siteId
);

    return (response () -> json ([
        'message' => 'department created successfully',
        'data' => $department
    ], 201));


 }




public function getDepartmentsbySite(Request $request, string $siteId)
{
    $departments = $this->organisationService->listDepartments(
        $request->user(),
        $siteId,
    );

    return response()->json(['data' => $departments], 201);
}








}
