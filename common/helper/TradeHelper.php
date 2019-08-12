<?php
namespace asbamboo\qirifu\common\helper;

use asbamboo\di\ContainerAwareTrait;
use asbamboo\qirifu\common\model\trade\Entity AS TradeEntity;
use asbamboo\qirifu\common\model\trade\Manager AS TradeManager;
use asbamboo\qirifu\common\model\merchantChannel\Code AS MerchantChannelCode;
use asbamboo\qirifu\common\asbamboo\tradeQuery\TradeQueryRequest;
use asbamboo\qirifu\common\asbamboo\tradeCancel\TradeCancelRequest;
use asbamboo\qirifu\common\model\trade\Repository AS TradeRepository;
use asbamboo\qirifu\common\Constant AS CommonConstant;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use asbamboo\qirifu\common\exception\SystemException;
use asbamboo\qirifu\common\model\trade\Code AS TradeCode;
use asbamboo\qirifu\common\model\merchantChannel\Repository AS MerchantChannelRepository;

class TradeHelper
{
    use ContainerAwareTrait;

    /**
     *
     * @param string $qirifu_trade_no
     * @return TradeEntity
     */
    public function syncStatus(string $qirifu_trade_no) : TradeEntity
    {
        /**
         * services
         * @var TradeRepository $TradeRepository
         * @var TradeManager $TradeManager
         * @var TradeQueryRequest $TradeQueryRequest
         * @var MerchantChannelRepository $MerchantChannelRepository
         */
        $TradeRepository            = $this->Container->get(TradeRepository::class);
        $TradeQueryRequest          = $this->Container->get(TradeQueryRequest::class);
        $MerchantChannelRepository  = $this->Container->get(MerchantChannelRepository::class);

        $TradeEntity                        = $TradeRepository->findOneByQirifuTradeNo($qirifu_trade_no);

        $merchant_channel_type              = $TradeEntity->getMerchantChannelType();
        $user_id                            = $TradeEntity->getUserId();
        $MerchantChannelEntity              = $MerchantChannelRepository->findOneByTypeAndUserId($user_id, $merchant_channel_type);
        $merchant_channel_key_info          = $MerchantChannelEntity->getKeyInfo();

        $TradeQueryRequest->out_trade_no    = $TradeEntity->getQirifuTradeNo();

        $TradeQueryRequest->byDefaultAppKey();
        if($merchant_channel_type == MerchantChannelCode::TYPE_ALIPAY){
            $TradeQueryRequest->third_part      = json_encode(['app_auth_token' => $merchant_channel_key_info['app_auth_token']]);
        }else if($TradeEntity->getMerchantChannelType() == MerchantChannelCode::TYPE_WXPAY){
            if($merchant_channel_key_info['sub_mch_id'] == \Parameter::instance()->get('WXPAY_OWNER_MCH_ID')){
                $TradeQueryRequest->byOwnerWxpay();
            }else{
                $TradeQueryRequest->third_part = json_encode(['sub_mch_id' => $merchant_channel_key_info['sub_mch_id']]);
            }
        }

        $TradeQueryResponse                 = $TradeQueryRequest->exec();

        if($TradeQueryResponse->trade_status == 'CANCEL' && $TradeEntity->getStatus() != TradeCode::STATUS_CANCLE){
            // 取消 ===> 请求订单取消
            $this->cancelTrade($TradeEntity, $merchant_channel_key_info);

        }else if($TradeQueryResponse->trade_status == 'NOPAY' || $TradeQueryResponse->trade_status == 'PAYFAILED' || $TradeQueryResponse->trade_status == 'PAYING'){
            // 状态为未支付 ===> 如果操过指定可支付时间， 那么请求订单取消
            if($TradeEntity->getCreateTime() < time() - CommonConstant::TRADE_TIMEOUT && $TradeEntity->getStatus() != TradeCode::STATUS_CANCLE){
                $this->cancelTrade($TradeEntity, $merchant_channel_key_info);
            }

        }else if($TradeQueryResponse->trade_status == 'PAYOK' && $TradeEntity->getStatus() != TradeCode::STATUS_PAYOK){
            // 已支付，已取消等状态变化 ===> 状态对应修改
            if($TradeQueryResponse->total_fee < bcmul($TradeEntity->getPrice(), 100)){
                throw new SystemException('ERROR:非法操作,实际支付金额小于交易应该支付金额1');
            }
            /**
             *
             * @var TradeManager $TradeManager
             * @var DbFactoryInterface $Db
             */
            $Db             = $this->Container->get(DbFactoryInterface::class);
            $TradeManager   = $this->Container->get(TradeManager::class);
            $TradeManager->load($TradeEntity);
            $TradeManager->updateChannelTradeNo($TradeQueryResponse->third_trade_no);
            $TradeManager->updatePayok();
            $Db->getManager()->flush();
        }else if($TradeQueryResponse->trade_status == 'PAYED' && $TradeEntity->getStatus() != TradeCode::STATUS_PAYED){
            // 已支付，已取消等状态变化 ===> 状态对应修改
            if($TradeQueryResponse->total_fee < bcmul($TradeEntity->getPrice(), 100)){
                throw new SystemException('ERROR:非法操作,实际支付金额小于交易应该支付金额2');
            }
            /**
             *
             * @var TradeManager $TradeManager
             * @var DbFactoryInterface $Db
             */
            $Db             = $this->Container->get(DbFactoryInterface::class);
            $TradeManager   = $this->Container->get(TradeManager::class);
            $TradeManager->load($TradeEntity);
            $TradeManager->updateChannelTradeNo($TradeQueryResponse->third_trade_no);
            $TradeManager->updatePayed();
            $Db->getManager()->flush();
        }

        return $TradeEntity;
    }

    /**
     *
     * @param TradeEntity $TradeEntity
     * @param array $merchant_channel_key_info
     */
    public function cancelTrade(TradeEntity $TradeEntity, array $merchant_channel_key_info = []) : void
    {
        /**
         *
         * @var TradeCancelRequest $TradeCancelRequest
         */
        $TradeCancelRequest                = $this->Container->get(TradeCancelRequest::class);
        $TradeCancelRequest->out_trade_no  = $TradeEntity->getQirifuTradeNo();
        if($TradeEntity->getMerchantChannelType() == MerchantChannelCode::TYPE_ALIPAY){
            $TradeCancelRequest->third_part  = json_encode(['app_auth_token' => $merchant_channel_key_info['app_auth_token']]);
        }else if($TradeEntity->getMerchantChannelType() == MerchantChannelCode::TYPE_WXPAY){
            if($merchant_channel_key_info['sub_mch_id'] == \Parameter::instance()->get('WXPAY_OWNER_MCH_ID')){
                $TradeCancelRequest->byOwnerWxpay();
            }else{
                $TradeCancelRequest->third_part = json_encode(['sub_mch_id' => $merchant_channel_key_info['sub_mch_id']]);
            }
        }
        $TradeCancelResponse               = $TradeCancelRequest->exec();


        if($TradeCancelResponse->trade_status == 'CANCEL'){
            /**
             *
             * @var TradeManager $TradeManager
             */
            $TradeManager  = $this->Container->get(TradeManager::class);
            $TradeManager->load($TradeEntity);
            $TradeManager->updateCancel();

            /**
             *
             * @var DbFactoryInterface $Db
             */
            $Db         = $this->Container->get(DbFactoryInterface::class);
            $Db->getManager()->flush();
        }
    }

     /**
      *
      * @param int $merchant_channel_type
      * @return string|NULL
      */
     public function getAsbambooChannel(int $merchant_channel_type) : ?string
     {
         switch ($merchant_channel_type){
            case MerchantChannelCode::TYPE_ALIPAY:
                return 'ALIPAY_ONECD';
                break;
            case MerchantChannelCode::TYPE_WXPAY:
                return 'WXPAY_ONECD';
                break;
            default:
                return null;
         }
     }
}
