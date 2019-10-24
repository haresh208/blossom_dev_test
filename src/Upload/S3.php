<?php
/**
 * Created by PhpStorm.
 * User: haresh
 * Date: 24/10/19
 * Time: 7:40 PM
 */
namespace Blossom\BackendDeveloperTest\Upload;

use Blossom\BackendDeveloperTest\UploadInterface;
use S3Stub\Client;

class S3 implements UploadInterface
{
    /**
     * @inheritdoc
     */
    public function uploadFile($file, $params){
        $s3                     = new Client($params['s3']['access_key_id'], $params['s3']['secret_access_key']);
        $fileObj                = $s3->send($file,$params['s3']['bucketname']);

        return array(
            'url'       => $fileObj->getPublicUrl(),
            'baseUriMp4'=> "http://".$params['s3']['bucketname'].".s3.amazonaws.com/"
        );
    }
}