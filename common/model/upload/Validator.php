<?php
namespace asbamboo\qirifu\common\model\upload;

use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\qirifu\common\exception\SystemException;

/**
 * 字段验证器
 *  - 确保字段时数据可写入的字段
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
trait Validator
{
    /**
     *
     * @param string $upload_path
     * @throws SystemException
     */
    public function validatePath(string $upload_path)
    {
        if(strlen($upload_path) > 255){
            throw new SystemException('文件路径无效。');
        }
    }

    /**
     *
     * @param string $upload_name
     * @throws MessageException
     */
    public function validateName(string $upload_name)
    {
        if(strlen($upload_name) > 255){
            throw new MessageException('文件名名太长，请修改文件名。');
        }
    }

    /**
     *
     * @param string $media_type
     * @throws SystemException
     */
    public function validateMediaType(string $media_type)
    {
        if(strlen($media_type) > 255){
            throw new SystemException('文件类型无效。');
        }
    }
}