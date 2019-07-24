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
         */
        $TradeRepository    = $this->Container->get(TradeRepository::class);
        $TradeQueryRequest  = $this->Container->get(TradeQueryRequest::class);

        $TradeEntity                        = $TradeRepository->findOneByQirifuTradeNo($qirifu_trade_no);
        $TradeQueryRequest->out_trade_no    = $TradeEntity->getQirifuTradeNo();
        $TradeQueryResponse                 = $TradeQueryRequest->exec();

        if($TradeQueryResponse->trade_status == 'CANCEL'){
            // 取消 ===> 请求订单取消
            $this->cancelTrade($TradeEntity);

        }else if($TradeQueryResponse->trade_status == 'NOPAY' || $TradeQueryResponse->trade_status == 'PAYFAILED' || $TradeQueryResponse->trade_status == 'PAYING'){
            // 状态为未支付 ===> 如果操过指定可支付时间， 那么请求订单取消
            if($TradeEntity->getCreateTime() < time() - CommonConstant::TRADE_TIMEOUT){
                $this->cancelTrade($TradeEntity);
            }

        }else if($TradeQueryResponse->trade_status == 'PAYOK'){
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
            $TradeManager->updatePayok();
            $Db->getManager()->flush();
        }else if($TradeQueryResponse->trade_status == 'PAYED'){
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
            $TradeManager->updatePayed();
            $Db->getManager()->flush();
        }

        return $TradeEntity;
    }

    public function cancelTrade(TradeEntity $TradeEntity) : void
    {
        /**
         *
         * @var TradeCancelRequest $TradeCancelRequest
         */
        $TradeCancelRequest                = $this->Container->get(TradeCancelRequest::class);
        $TradeCancelRequest->out_trade_no  = $TradeEntity->getQirifuTradeNo();
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
