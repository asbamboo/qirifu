<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\qirifu\common\alipay\WwwAuth AS AlipayWwwAuth;
use asbamboo\qirifu\common\asbamboo\tradePay\TradePayRequest;
use asbamboo\qirifu\common\model\merchantChannel\Repository AS MerchantChannelRepository;
use asbamboo\qirifu\common\model\trade\Manager AS TradeManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use asbamboo\qirifu\common\asbamboo\tradePay\TradePayNotify;
use asbamboo\qirifu\common\model\trade\Repository AS TradeRepository;
use asbamboo\http\TextResponse;
use asbamboo\qirifu\common\model\trade\Code AS TradeCode;
use asbamboo\qirifu\common\exception\SystemException;
use asbamboo\router\RouterInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\qirifu\common\model\merchantChannel\Code AS MerchantChannelCode;
use asbamboo\qirifu\common\wxpay\WwwAuth AS WxpayWwwAuth;

class Trade extends ControllerAbstract
{
    public function channels()
    {
        $channels   = [];
        foreach( MerchantChannelCode::TYPES AS $type => $label){
            $channels[] = ['key' => $type, 'label' => $label];
        }
        return $this->successJson('success', [
            'channels'  => $channels,
        ]);
    }

    public function lists(ServerRequestInterface $Request)
    {
        try{
            $data           = ['total' => 0, 'items'=>[]];

            /**
             *
             * @var TradeRepository $TradeRepository
             * @var UserTokenInterface $UserToken
             * @var \asbamboo\qirifu\common\model\trade\Entity $TradeEntity
             */
            $TradeRepository        = $this->Container->get(TradeRepository::class);
            $UserToken              = $this->Container->get(UserTokenInterface::class);
            $Pagintor               = $TradeRepository->getPaginatorByWww($Request, $UserToken->getUser()->getUserId());
            $data['total']          = $Pagintor->count();
            foreach($Pagintor->getIterator() AS $TradeEntity){
                $data['items'][]    = [
                    'seq'           => $TradeEntity->getSeq(),
                    'channel'       => ['key' => $TradeEntity->getMerchantChannelType(), 'label' => MerchantChannelCode::TYPES[$TradeEntity->getMerchantChannelType()]],
                    'amount'        => number_format($TradeEntity->getPrice(), 2),
                    'in_trade_no'   => $TradeEntity->getQirifuTradeNo(),
                    'out_trade_no'  => $TradeEntity->getChannelTradeNo(),
                    'create_ymdhis' => date('Y-m-d H:i:s', $TradeEntity->getCreateTime()),
                    'pay_ymdhis'    => date('Y-m-d H:i:s', $TradeEntity->getPayokTime()),
                ];
            }

            return $this->successJson("success", $data);

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
