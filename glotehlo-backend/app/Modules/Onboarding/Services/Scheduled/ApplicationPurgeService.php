<?php

namespace App\Modules\Onboarding\Services\Scheduled;

use Illuminate\Support\Facades\Log;

use Cloudinary\Uploader;

use app\Modules\Onboarding\Models\Application;

class  ApplicationPurgeService
{
    private array $urlColumns =[
        'cv',
        'handwritten_letter',
        'recommendation_letter',
        'attestation_of_attendance',
        'profile_picture'
    ];

    public function purge(Application $application) : bool
    {
        try{

            foreach ($this ->urlColumns as $column) {
                $url = $application -> $column;

                if(empty($url)) {
                    continue;
                }

                $parsed = $this-> parseCloudinaryUrl($url);

                if ($parsed === null) {
                    Log::warning('could not parse Cloudinary URL', [
                        'application_id'  =>$application->id,
                        'column' => $column,
                    ]);

                    return false;
                }

                $deleted = $this ->deleteFile(
                    $parsed['public_id'],
                    $parsed['type']
                );

                if(!$deleted) {
                    Log::warning ('cloudinary file deletion failed', [
                        'application_id' => $application->id,
                        'column' =>$column,

                        'public_id' => $parsed['public_id'],
                    ]);

                    return false;
                }
            }

            $application->delete();

            return true;
        } catch (\Throwable $e) {


            Log::error('Application purge failed',
            [
             'application_id' => $application->id,
             'error' => $e->getMessage(),
            ]);

            return false;

        }
    }


    private function parseCloudinaryUrl(string $url): ?array
    {

        if(!str_contains($url, 'res.cloudinary.com'))
        {
             return null;
        }

        preg_match( '#res\.cloudinary\.com/[^/]+/(image|raw|video)/upload/#',
        $url,
        $matches);


        if (empty($matches)) {
         return null;
        }

        $type = $matches[1];


        //get the resource type oath and source identifier
        $path = parse_url($url, PHP_URL_PATH);

        if ($path === null || $path === false)
         {
            return null;
         }

        //getiing the file indentifier

        $publicId = preg_replace(
            '#^/[^/]+/(image|raw|video)/upload/v[0-9]+/#',
            '',
             $path
        );

        if ($type === 'image')
        {
         $publicId = preg_replace('/\.[^.\/]+$/', '', $publicId);
        }


        return [
            'type' => $type,
            'public_id' => $publicId,
            ];

    }



    private function deleteFile(string $publicId, string $type): bool
    {
    \Cloudinary::config([
        'cloud_name' => config('services.cloudinary.cloud_name'),
        'api_key'    => config('services.cloudinary.api_key'),
        'api_secret' => config('services.cloudinary.api_secret'),
    ]);

    $result = Uploader::destroy($publicId, ['resource_type' => $type]);

    return in_array($result['result'], ['ok', 'not found'], true);
    }


}
