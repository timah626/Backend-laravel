<?php


namespace  App\Modules\Onboarding\Repository;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Modules\Onboarding\Models\Application;

use Illuminate\Database\Eloquent\Collection;

use Carbon\Carbon;








class ApplicationRepository
{


	public function findApplicationByEmail(string $email): ?Application
	{

		return Application::where('email', $email)->first();
	}



	public function findApplicationById(string $applicationId): ?Application
	{

		return Application::where('id', $applicationId)->lockForUpdate()->first();
	}



	public function createApplication(array $data): Application
	{
		return Application::create($data);
	}





	public function getByStatus(string $status, int $perPage = 10): LengthAwarePaginator
	{
		return Application::where('status', $status)
                          ->with('suggestedDepartment:id,name')
			              ->orderByDesc('submitted_at')
			              ->select('id', 'first_name', 'last_name', 'email', 'school', 'submitted_at', 'rejected_at', 'status', 'gender', 'major', 'level','suggested_department_id')
			              ->paginate($perPage);
	}





    public function markAsApproved (Application $application,   string $userId): Application
    {

            $application -> status = 'approved';
            $application -> user_id = $userId;
            $application -> rejected_at = null;
            $application -> save();


            return $application;


    }


    public function markAsRejected (Application $application) :Application
    {

            $application -> status = 'rejected';
            $application -> rejected_at = now();
            $application -> save();

            return $application;


    }


    public function findRejectedBeforeTenDays (Carbon $cutoff) :Collection
    {
        return Application :: where ('status', 'rejected')
                           -> where ( 'rejected_at' ,'<', $cutoff)
                           -> get();


    }





    //gotta remind myself to secure this and add userchecks
    public function deleteApplicationById ( string $applicationId)
    {
         return Application::where('id', $applicationId)->delete();
    }



    public function getApplicationByDepartmentId (string $departmentId , int $perPage = 10): LengthAwarePaginator
    {
        return Application::where ('suggested_department_id', $departmentId)
                          ->paginate($perPage);
    }







}





