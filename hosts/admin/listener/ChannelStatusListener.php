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
use asbamboo\qirifu\common\model\merchant\Repository AS MerchantRepository;
use asbamboo\qirifu\common\email\GeneralEmailManager;

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
            $content .= '</br>请您修改或添加相关<a href="#/information/merchant"><i>商户资料后</i></a>，重新提交申请。';
        }
        if($this->isNeedAuthorization($MerchantChannelEntity)){
            $content .= '</br>请前往<a href="#/information/channel"><i>支付通道页面</i></a>授权。';
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
     * @param ServerRequestInterface $Request
     * @param MerchantChannelEntity $MerchantChannelEntity
     * @param MerchantChannelLogEntity $MerchantChannelLogEntity
     */
    public function onChangeSendEmail(ServerRequestInterface $Request, MerchantChannelEntity $MerchantChannelEntity, MerchantChannelLogEntity $MerchantChannelLogEntity)
    {
        /**
         *
         * 判断是否需要发送email
         * @var array|null $notifys
         */
        $notifys    = $Request->getRequestParam('notifys');
        if(!in_array('email', $notifys)){
            return;
        }

        /**
         *
         * @var MerchantRepository $MerchantRepository
         */
        $MerchantRepository = $this->Container->get(MerchantRepository::class);
        $MerchantEntity     = $MerchantRepository->findOneBySeq($MerchantChannelEntity->getMerchantSeq());
        $to_mail            = $MerchantEntity->getLinkEmail();
        if(empty($to_mail) || strpos($to_mail, '@') == false){
            return; // 联系人邮箱没填写不能发送邮件
        }

        $title              = '您申请开通支付通道[' . MerchantChannelCode::TYPES[$MerchantChannelEntity->getType()] . ']，状态发生改变（' . $MerchantChannelLogEntity->getMerchantStatusLabel() . '）';
        $content            = '您申请开通支付通道[' . MerchantChannelCode::TYPES[$MerchantChannelEntity->getType()] . ']，状态发生改变（' . $MerchantChannelLogEntity->getMerchantStatusLabel() . '）'
                            . '<br/>'
                            . $MerchantChannelLogEntity->getDesc();

        if($this->isNeedModifyMerchantData($MerchantChannelEntity)){
            $content .= '</br>请您修改或添加相关<a href="' . \Parameter::instance()->get('SYSTEM_WWW_BASE_URL') . '#/information/merchant"><i>商户资料后</i></a>，重新提交申请。';
        }
        if($this->isNeedAuthorization($MerchantChannelEntity)){
            $content .= '</br>请前往<a href="' . \Parameter::instance()->get('SYSTEM_WWW_BASE_URL') . '#/information/channel"><i>支付通道页面</i></a>授权。';
        }
        $content .= '<br/><a href="' . \Parameter::instance()->get('SYSTEM_WWW_BASE_URL') . '">' . \Parameter::instance()->get('SYSTEM_WWW_BASE_URL') . '</a>';

        /**
         * 发送注册邮件
         * @var GeneralEmailManager $GeneralEmailManager
         */
        $GeneralEmailManager    = $this->Container->get(GeneralEmailManager::class);
        $GeneralEmailManager->sendTo($to_mail, $title, $content);
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
            case MerchantChannelCode::STATUS_THIRD_REFUSE:
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
