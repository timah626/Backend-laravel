<?php

namespace App\Modules\Organisation\Repository;
use App\Modules\Organisation\Models\Department;

use Illuminate\Database\Eloquent\Collection;

use App\Modules\Organisation\Models\Site;




class OrganisationRepository {


    public function findDepartment(string $departmentId): ?Department
    {
        return Department::where('id', $departmentId)->first();
    }



    public function createSite(array $data): ?Site {

      return Site::create ($data);

}



    public function createDepartment (array $validatedData): ?Department {

      return Department::create($validatedData);
    }





public function findDepartmentbySite(string $siteId): Collection
{
    return Department::where('site_id', $siteId)
            ->get();
}




    public function findSite(string $siteId): ?Site
    {
        return Site::where('id', $siteId)->first();
    }



     public function findDepartmentBySlug(string $slug): ?Department
    {
        return Department::where('qr_slug', $slug)->first();
    }


}






