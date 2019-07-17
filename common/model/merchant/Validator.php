<?php
namespace asbamboo\qirifu\common\model\merchant;

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
     * @param string $merchant_name
     * @throws MessageException
     */
    public function validateName(string $merchant_name)
    {
        if(empty($merchant_name)){
            throw new MessageException('请填写商户简称。');
        }
        if(mb_strlen($merchant_name) > 45){
            throw new MessageException('商户简称过长，超过有效范围[45]。');
        }
    }

    /**
     *
     * @param string $merchant_link_man
     * @throws MessageException
     */
    public function validateLinkMan(string $merchant_link_man)
    {
        if(empty($merchant_link_man)){
            throw new MessageException('请填写联系人姓名。');
        }
        if(mb_strlen($merchant_link_man) > 255){
            throw new MessageException('联系人名称过长，超过有效范围[255]。');
        }
    }

    /**
     *
     * @param string $merchant_link_phone
     * @throws MessageException
     */
    public function validateLinkPhone(string $merchant_link_phone)
    {
        if(empty($merchant_link_phone)){
            throw new MessageException('请填写联系人电话。');
        }
        if(mb_strlen($merchant_link_phone) > 255){
            throw new MessageException('联系人电话过长，超过有效范围[255]。');
        }
    }
}