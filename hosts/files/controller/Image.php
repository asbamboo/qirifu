<?php
namespace asbamboo\qirifu\hosts\files\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\Response;
use asbamboo\http\Constant AS HttpConstant;
use asbamboo\http\Stream;
use asbamboo\qirifu\common\Constant;
use asbamboo\qirifu\common\model\upload\Repository AS UploadRepository;

class Image extends ControllerAbstract
{
    /**
     *
     * @param string $image
     */
    public function read(string $fileid)
    {
        /**
         *
         * @var UploadRepository $UploadRepository
         */
        $UploadRepository       = $this->Container->get(UploadRepository::class);
        $UploadEntity           = $UploadRepository->findOneByFileid($fileid);
        $upload_path            = $UploadEntity->getPath();
        $upload_media_type      = $UploadEntity->getMediaType();
        $image_absolute_path    = Constant::FILE_IMAGE_ROOT_PATH . DIRECTORY_SEPARATOR . $upload_path;

        $Body           = new Stream($image_absolute_path, 'rb');
        return new Response($Body, HttpConstant::STATUS_OK, [
            'content-type'  => $upload_media_type,
        ]);
    }
}