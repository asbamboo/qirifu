<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\http\ServerRequestInterface;
use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\qirifu\common\alipay\Auth AS AlipayAuth;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\qirifu\common\model\merchantChannel\Repository AS MerchantChannelRepository;
use asbamboo\qirifu\common\model\merchantChannel\Code AS MerchantChannelCode;
use asbamboo\qirifu\common\model\merchantChannel\Manager As MerchantChannelManager;
use asbamboo\qirifu\common\model\merchantChannelLog\Manager AS MerchantChannelLogManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;

class Alipay extends ControllerAbstract
{
    public function auth(ServerRequestInterface $Request)
    {

        try{
            /**
             *
             * @var AlipayAuth $AlipayAuth
             * @var UserTokenInterface $UserToken
             * @todo 这里获取tokeninfo后面修改成服务器空闲时执行
             */
            $AlipayAuth         = $this->Container->get(AlipayAuth::class);
            $alipay_auth_code   = $AlipayAuth->getAppAuthCode($Request);
            $alipay_auth_info   = $AlipayAuth->getAppAuthTokenInfo($alipay_auth_code);
            $UserToken          = $this->Container->get(UserTokenInterface::class);
            $User               = $UserToken->getUser();

            /**
             *
             * @var MerchantChannelRepository $MerchantChannelRepository
             * @var MerchantChannelManager $MerchantChannelManager
             * @var MerchantChannelLogManager $MerchantChannelLogManager
             * @var DbFactoryInterface $Db
             */
            $MerchantChannelRepository  = $this->Container->get(MerchantChannelRepository::class);
            $MerchantChannelManager     = $this->Container->get(MerchantChannelManager::class);
            $MerchantChannelLogManager  = $this->Container->get(MerchantChannelLogManager::class);
            $Db                         = $this->Container->get(DbFactoryInterface::class);
            $MerchantChannelEntity      = $MerchantChannelRepository->findOneByTypeAndUserId($User->getUserId(), MerchantChannelCode::TYPE_ALIPAY);
            $MerchantChannelManager->load($MerchantChannelEntity);
            $MerchantChannelManager->updateStatus(MerchantChannelCode::STATUS_OK, $alipay_auth_info);
            $MerchantChannelLogManager->load();
            $MerchantChannelLogManager->create($MerchantChannelEntity, "SYSTEM:用户授权成功，支付宝支付正式开通。");
            $Db->getManager()->flush();

            return $this->successJson("授权成功，支付宝支付正式开通");
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }
}