<?php
namespace asbamboo\qirifu\hosts\files\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\ServerRequestInterface;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\qirifu\common\Constant;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\router\RouterInterface;
use asbamboo\qirifu\common\exception\SystemException;
use asbamboo\qirifu\common\model\upload\Manager AS UploadManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;

class Upload extends ControllerAbstract
{
    public function image(ServerRequestInterface $ServerRequest, $path = '')
    {
        try
        {
            /**
             *
             * @var \asbamboo\http\UploadedFileInterface $Upfile
             */
            $Upfile            = $ServerRequest->getUploadedFiles()['file'];

            if(empty($Upfile)){
                throw new MessageException('请选择上传图片.');
            }

            /**
             * @var UploadManager $UploadManager
             * @var RouterInterface $Router
             */
            $Router             = $this->Container->get(RouterInterface::class);
            $UploadManager      = $this->Container->get(UploadManager::class);
            $client_file_name   = $Upfile->getClientFilename();
            $file_extendsion    = strrchr($client_file_name, '.');
            $fileid             = $UploadManager->genrateFileid();
            $file_name          = $fileid . $file_extendsion;
            $url                = $Router->generateAbsoluteUrl('image_read', ['fileid' => $fileid]);
            $media_type         = $Upfile->getClientMediaType();
            $relative_file_path = $path . DIRECTORY_SEPARATOR . $file_name;
            $file_dir           = Constant::FILE_IMAGE_ROOT_PATH . DIRECTORY_SEPARATOR . $path;
            $file_path          = Constant::FILE_IMAGE_ROOT_PATH . DIRECTORY_SEPARATOR . $relative_file_path;

            @mkdir($file_dir, 0755, true);

            if($Upfile->moveTo($file_path) == false){
                throw new MessageException('图片上传失败：' . $Upfile->getError());
            }

            $UploadManager->load();
            $UploadManager->create($fileid, $client_file_name, $relative_file_path, $media_type);

            /**
             *
             * @var DbFactoryInterface $Db
             */
            $Db             = $this->Container->get(DbFactoryInterface::class);
            $Db->getManager()->flush();

            return $this->json([
                "fileid"        => $fileid,
                "name"          => $client_file_name,
                "url"           => $url,
            ]);
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(SystemException $e){
            $error_message      = "系统异常";
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                $error_message  = (string) $e;
            }
            return $this->failedJson($error_message);
        }
    }
}