<?php
/**
 * Created by PhpStorm.
 * User: haresh
 * Date: 24/10/19
 * Time: 7:40 PM
 */
namespace Blossom\BackendDeveloperTest\Upload;

use Blossom\BackendDeveloperTest\UploadInterface;
use FTPStub\FTPUploader;

class FTP implements UploadInterface
{
    /**
     * @inheritdoc
     */
    public function uploadFile($file, $params){

        $ftp = new FTPUploader($file, $params['ftp']['hostname'], $params['ftp']['username'],$params['ftp']['password']
            ,$params['ftp']['destination']);

        return array('url' => "ftp://".$params['ftp']['hostname']."/".$params['ftp']['destination']."/".$file->getFileName());
    }
}