<?php
namespace asbamboo\qirifu\hosts\admin\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\model\trade\Repository AS TradeRepository;
use asbamboo\qirifu\common\model\merchantChannel\Code AS MerchantChannelCode;
use asbamboo\qirifu\common\model\trade\Code AS TradeCode;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\qirifu\common\model\merchant\Repository AS MerchantRepository;

class Trade extends ControllerAbstract
{
    public function searchOptions(ServerRequestInterface $Request)
    {
        $channels   = [];
        foreach( MerchantChannelCode::TYPES AS $type => $label){
            $channels[] = ['key' => $type, 'label' => $label];
        }

        $statuss   = [];
        foreach( TradeCode::STATUSS AS $status => $label){
            if($status == TradeCode::STATUS_PAYOK ){
                continue;
            }
            $statuss[] = ['key' => $status, 'label' => $label];
        }

        return $this->successJson('success', [
            'channels'  => $channels,
            'statuss'   => $statuss,
        ]);
    }

    public function lists(ServerRequestInterface $Request)
    {
        try{
            $data           = ['total' => 0, 'items'=>[]];

            /**
             *
             * @var TradeRepository $TradeRepository
             * @var MerchantRepository $MerchantRepository
             * @var UserTokenInterface $UserToken
             * @var \asbamboo\qirifu\common\model\trade\Entity $TradeEntity
             */
            $TradeRepository        = $this->Container->get(TradeRepository::class);
            $MerchantRepository     = $this->Container->get(MerchantRepository::class);
            $Pagintor               = $TradeRepository->getPaginatorByAdmin($Request);
            $data['total']          = $Pagintor->count();
            foreach($Pagintor->getIterator() AS $TradeEntity){
                $data['items'][]    = [
                    'seq'           => $TradeEntity->getSeq(),
                    'user_id'       => $TradeEntity->getUserId(),
                    'channel'       => ['key' => $TradeEntity->getMerchantChannelType(), 'label' => MerchantChannelCode::TYPES[$TradeEntity->getMerchantChannelType()]],
                    'status'        => ['key' => $TradeEntity->getStatus(), 'label' => TradeCode::STATUSS[$TradeEntity->getStatus()]],
                    'amount'        => number_format($TradeEntity->getPrice(), 2),
                    'in_trade_no'   => $TradeEntity->getQirifuTradeNo(),
                    'out_trade_no'  => $TradeEntity->getChannelTradeNo(),
                    'create_ymdhis' => $TradeEntity->getCreateTime() ? date('Y-m-d H:i:s', $TradeEntity->getCreateTime()) : '-',
                    'pay_ymdhis'    => $TradeEntity->getPayokTime() ? date('Y-m-d H:i:s', $TradeEntity->getPayokTime()) : '-',
                ];
            }
            $MerchantRepository->mappingNames($data['items']);

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