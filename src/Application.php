<?php
namespace Blossom\BackendDeveloperTest;

use FFMPEGStub\FFMPEG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * You should implement this class however you want.
 * 
 * The only requirement is existence of public function `handleRequest()`
 * as this is what is tested. The constructor's signature must not be changed.
 */
class Application
{

    /**
     * @var array
     */
    protected $configParams;

    /**
     * @var integer
     */
    protected $statusCode;

    /**
     * By default the constructor takes a single argument which is a config array.
     *
     * You can handle it however you want.
     * 
     * @param array $config Application config.
     */
    public function __construct(array $config)
    {
        $this->configParams = $config;
    }

    /**
     * This method should handle a Request that comes pre-filled with various data.
     *
     * You should implement it however you want and it should return a Response
     * that passes all tests found in EncoderTest.
     * 
     * @param  Request $request The request.
     *
     * @return Response
     */
    public function handleRequest(Request $request): Response
    {
        $result = array();
        $response = new Response();
        $response->setCharset('UTF-8')
            ->headers->set('Content-Type', 'application/json');


        // return if request method is not POST method
        if($request->getMethod() != "POST"){

            return $response->setContent(json_encode(array("error" => "Request method is not allowed")))
                ->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED);
        }

        //return if null request found
        if($request->request == NULL){

            return $response->setContent(json_encode(array("error" => "Null request found")))
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $formats    = $request->get('formats');
        $uploadType = $request->get('upload');
        $file       = $request->files->get('file');

        // return if not parameters found return as bad request
        if(empty($formats) && empty($uploadType)){

            return $response->setContent(json_encode(array("error" => "Parameters not found")))
                ->setStatusCode(Response::HTTP_BAD_REQUEST);

        }

        // return if file is not in request
        if(!$file || $file == NULL){

            return $response->setContent(json_encode(array("error" => "File not found")))
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        try{
            if(isset($uploadType) && !empty($uploadType)){

                $uploadFactory = new UploadFactory();
                $uploadClient = $uploadFactory->getUploadChannel($uploadType);

                if(!$uploadClient){
                    return $response->setContent(json_encode(array("error" => "Unknown type found")))
                        ->setStatusCode(Response::HTTP_BAD_REQUEST);
                }

                $data = $uploadClient->uploadFile($file, $this->configParams);

                if($data){
                    $result['url'] = $data['url'];
                }
            }

        }catch (\InvalidArgumentException $ex){
            return $response->setContent(json_encode(array("error" => $ex->getMessage())))
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }catch (\RuntimeException $ex){
            return $response->setContent(json_encode(array("error" => $ex->getMessage())))
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }catch (\Exception $ex){
            return $response->setContent(json_encode(array("error" => $ex->getMessage())))
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }



        // conver files for required format
        foreach ($formats as $format){

            switch ($format){
                case FormatType::MP4:
                    $ffmpeg = new FFMPEG();
                    $convFileObj = $ffmpeg->convert($file);
                    $formats[$format] = $data['baseUriMp4'].$convFileObj->getFilename();
                    break;
                case FormatType::WEBM:
                    $encoding = new \EncodingStub\Client($this->configParams['encoding.com']['app_id'],
                        $this->configParams['encoding.com']['access_token']);
                    $formats[$format] = $encoding->encodeFile($file, $format);
                    break;
                case FormatType::OGV:
                    $encoding = new \EncodingStub\Client($this->configParams['encoding.com']['app_id'],
                        $this->configParams['encoding.com']['access_token']);
                    $formats[$format] = $encoding->encodeFile($file, $format);
                    break;
                default:
                    return $response->setContent(json_encode(array("Error" => "Format is not supported")))
                        ->setStatusCode(Response::HTTP_BAD_REQUEST);
                    break;
            }

        }

        $result['formats'] = $formats;

        $response->setContent(json_encode($result))
            ->setStatusCode(Response::HTTP_OK);
        return $response;

    }


}
