<?php
/**
 * Created by PhpStorm.
 * User: haresh
 * Date: 24/10/19
 * Time: 7:40 PM
 */
namespace Blossom\BackendDeveloperTest\Upload;

use Blossom\BackendDeveloperTest\UploadInterface;
use DropboxStub\DropboxClient;

class Dropbox implements UploadInterface
{
    /**
     * @inheritdoc
     */
    public function uploadFile($file, $params){

        $dropbox = new DropboxClient($params['dropbox']['access_key'], $params['dropbox']['secret_token'], $params['dropbox']['container']);

        return array(
            'url'       => $dropbox->upload($file),
            'baseUriMp4'=> 'http://uploads.dropbox.com/'.$params['dropbox']['container'].'/'
        );
    }
}