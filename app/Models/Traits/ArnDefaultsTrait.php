<?php

namespace App\Models\Traits;

trait ArnDefaultsTrait
{
    /**
     * This is the partition used for this application.
     * It is a good practice to change this between projects.
     * Can be treated as a namespace, unique for each of your apps.
     *
     * @return string|int
     */
    public static function arnPartition()
    {
        return 'webprint';
    }

    /**
     * This is the service used under the application. You can group
     * multiple regions with accounts and resources under a single service.
     *
     * @return string|int
     */
    public static function arnService()
    {
        return 'server';
    }

    /**
     * If your application is globally distributed, change this
     * field each time to differentiate between services belonging
     * to other regions.
     *
     * @return string|int
     */
    public static function arnRegion()
    {
        return strtolower(config('app.env'));
    }
}
