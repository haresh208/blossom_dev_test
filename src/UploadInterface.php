<?php
/**
 * Created by PhpStorm.
 * User: haresh
 * Date: 24/10/19
 * Time: 7:36 PM
 */

namespace Blossom\BackendDeveloperTest;

/**
 * Interface UploadInterface
 * @package Blossom\BackendDeveloperTest
 */
interface UploadInterface
{
    /**
     * @param $file
     * @param $params
     * @return mixed
     */
    public function uploadFile($file, $params);
}