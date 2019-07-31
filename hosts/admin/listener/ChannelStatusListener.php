<?php
namespace asbamboo\qirifu\hosts\admin\listener;

use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\model\merchantChannel\Entity AS MerchantChannelEntity;
use asbamboo\qirifu\common\model\merchantChannelLog\Entity AS MerchantChannelLogEntity;
use asbamboo\di\ContainerAwareTrait;
use asbamboo\qirifu\common\model\message\Manager AS MessageManager;
use asbamboo\qirifu\common\model\messageDetail\Manager AS MmessageDetailManager;
use asbamboo\qirifu\common\model\merchantChannel\Code AS MerchantChannelCode;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\di\ContainerInterface;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;

/**
 * 支付通道申请的状态变更
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2019年7月31日
 */
class ChannelStatusListener
{
    use ContainerAwareTrait;

    /**
     *
     * @param ContainerInterface $Container
     */
    public function __construct(ContainerInterface $Container)
    {
        $this->setContainer($Container);
    }

    /**
     *
     * @param ServerRequestInterface $Request
     * @param MerchantChannelEntity $MerchantChannelEntity
     * @param MerchantChannelLogEntity $MerchantChannelLogEntity
     */
    public function onChangeCreateMessage(ServerRequestInterface $Request, MerchantChannelEntity $MerchantChannelEntity, MerchantChannelLogEntity $MerchantChannelLogEntity)
    {
        /**
         *
         * @var UserTokenInterface $UserToken
         */
        $UserToken      = $this->Container->get(UserTokenInterface::class);
        $from_user_id   = $UserToken->getUser()->getUserId();
        $to_user_id     = $MerchantChannelEntity->getUserId();
        $title          = '您申请开通支付通道[' . MerchantChannelCode::TYPES[$MerchantChannelEntity->getType()] . ']，状态发生改变（' . $MerchantChannelLogEntity->getMerchantStatusLabel() . '）';
        $content        = '您申请开通支付通道[' . MerchantChannelCode::TYPES[$MerchantChannelEntity->getType()] . ']，状态发生改变（' . $MerchantChannelLogEntity->getMerchantStatusLabel() . '）'
                        . '<br/>'
                        . $MerchantChannelLogEntity->getDesc();

        if($this->isNeedModifyMerchantData($MerchantChannelEntity)){
            $content .= '</br>请您修改或添加相关<a href="#/information/merchant">商户资料后</a>，重新提交申请。';
        }
        if($this->isNeedAuthorization($MerchantChannelEntity)){
            $content .= '</br>请前往<a href="#/information/channel">支付通道页面</a>授权。';
        }

        /**
         *
         * @var MessageManager $MessageManager
         * @var MmessageDetailManager $MmessageDetailManager
         */
        $Db = $this->Container->get(DbFactoryInterface::class);
        $Db->getManager()->transactional(function()use($Db, $from_user_id, $to_user_id, $title, $content){

            $MessageManager         = $this->Container->get(MessageManager::class);
            $MmessageDetailManager  = $this->Container->get(MmessageDetailManager::class);
            $MessageEntity          = $MessageManager->load();
            $MessageManager->create($from_user_id, $to_user_id, $title);
            $Db->getManager()->flush();

            $MmessageDetailManager->load();
            $MmessageDetailManager->create($MessageEntity, $content);
        });
    }

    /**
     *
     * @param MerchantChannelEntity $MerchantChannelEntity
     * @return bool
     */
    private function isNeedModifyMerchantData(MerchantChannelEntity $MerchantChannelEntity) : bool
    {
        switch ($MerchantChannelEntity->getStatus()){
            case MerchantChannelCode::STATUS_REFUSE:
            case MerchantChannelCode::STATUS_RESEND_THIRD:
                return true;
            default:
                return false;
        }
        return false;
    }

    /**
     *
     * @param MerchantChannelEntity $MerchantChannelEntity
     * @return bool
     */
    private function isNeedAuthorization(MerchantChannelEntity $MerchantChannelEntity) : bool
    {
        switch ($MerchantChannelEntity->getStatus()){
            case MerchantChannelCode::STATUS_WAIT_AUTHORIZATION:
                return true;
            default:
                return false;
        }
        return false;
    }
}
