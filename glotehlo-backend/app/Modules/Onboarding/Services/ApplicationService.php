<?php



namespace App\Modules\Onboarding\Services;

use App\Exceptions\AppException;

use App\Modules\Onboarding\Repository\ApplicationRepository;

use App\Modules\Users\Repository\Admin\AdminRepository;





class ApplicationService {

   public function __construct(

   private ApplicationRepository $applicationRepository,
   private  AdminRepository $adminRepository


   ) {}


   public function application ( array $data) {




    $email = strtolower($data['email']);

    $existingApplication = $this->applicationRepository ->findApplicationByEmail($email);

    $existingUser = $this -> adminRepository -> findByEmail ($email);


    if ($existingApplication ) {

        return ['already_submitted' => true, 'status' => 'pending'];
    }


    if ($existingUser) {
         throw new AppException(
          'EMAIL_TAKEN',
          'email is taken',
           409
      );
    }


     $prefix = 'https://res.cloudinary.com/' . config('services.cloudinary.cloud_name') . '/';

         foreach (['cv', 'handwritten_application', 'recommendation_letter',
          'attestation_of_attendance','profile_picture'] as $file)

          {

           if (! str_starts_with($data[$file], $prefix))
             {
             throw new AppException(
                'VALIDATION_FAILED',
                'Not from Cloudinary',
                 422
              );
        }
      }

     $application = $this -> applicationRepository -> createApplication ([

                'first_name'                   => $data['first_name'],
                'last_name'                    => $data['last_name'],
                'email'                        => $email,
                'school'                       => $data['school'],
                'level'                        => $data['level'],
                'major'                        => $data['major'],
                'cv'                           => $data['cv'],
                'gender'                       => $data['gender'],
                'suggested_department_id'      => $data['suggested_department_id'],
                'suggested_start_date'         => $data['suggested_start_date'],
                'suggested_end_date'           => $data['suggested_end_date'],
                'status'                       => 'pending',
                'submitted_at'                 => now(),
                'handwritten_application'      => $data['handwritten_application'],
                'attestation_of_attendance'    => $data['attestation_of_attendance'],
                'recommendation_letter'        => $data['recommendation_letter'],
                'profile_picture'              => $data['profile_picture'],

     ]);

     return ['already_submitted' => false, 'status' => 'pending'];


   }


}

