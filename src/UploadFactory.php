<?php
/**
 * Created by PhpStorm.
 * User: haresh
 * Date: 24/10/19
 * Time: 7:35 PM
 */

namespace Blossom\BackendDeveloperTest;
use Blossom\BackendDeveloperTest\Upload\Dropbox;
use Blossom\BackendDeveloperTest\Upload\FTP;
use Blossom\BackendDeveloperTest\Upload\S3;

/**
 * Class UploadFactory
 * @package Blossom\BackendDeveloperTest
 */
class UploadFactory
{
    public function getUploadChannel($type){

        switch ($type) {
            case UploadType::FTP:
                return new FTP();
                break;
            case UploadType::S3:
                return new S3();
                break;
            case UploadType::DROPBOX:
                return new Dropbox();
                break;
            default:
                return false;
                break;
        }
    }
}