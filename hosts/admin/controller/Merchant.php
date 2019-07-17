<?php
namespace asbamboo\qirifu\hosts\admin\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\model\merchant\Repository AS MerchantRepository;
use asbamboo\qirifu\common\model\merchantDetail\Repository AS MerchantDetailRepository;
use asbamboo\qirifu\common\model\merchantChannel\Repository AS MerchantChannelRepository;
use asbamboo\qirifu\common\model\merchantChannel\Code AS MerchantChannelCode;
use asbamboo\qirifu\common\model\merchantChannelLog\Repository AS MerchantChannelLogRepository;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\router\RouterInterface;

class Merchant extends ControllerAbstract
{
    public function lists(ServerRequestInterface $Request)
    {
        try
        {
            $data           = ['total' => 0, 'items'=>[]];

            /**
             *
             * @var MerchantRepository $MerchantRepository
             * @var \asbamboo\qirifu\common\model\merchant\Entity $MerchantEntity
             */
            $MerchantRepository     = $this->Container->get(MerchantRepository::class);
            $Pagintor               = $MerchantRepository->getPaginatorByAdmin($Request);
            $data['total']          = $Pagintor->count();
            foreach($Pagintor->getIterator() AS $MerchantEntity){
                $data['items'][]    = [
                    'seq'           => $MerchantEntity->getSeq(),
                    'name'          => $MerchantEntity->getName(),
                    'link_man'      => $MerchantEntity->getLinkMan(),
                    'link_phone'    => $MerchantEntity->getLinkPhone(),
                    'create_ymdhis' => date('Y-m-d H:i:s', $MerchantEntity->getCreateTime()),
                    'update_ymdhis' => date('Y-m-d H:i:s', $MerchantEntity->getUpdateTime()),
                ];
            }

            return $this->successJson("处理成功", $data);
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function detail(int $seq = 0)
    {
        try{
            $merchant_seq   = $seq;
            $detail         = [];

            /**
             * @var MerchantDetailRepository $MerchantDetailRepository
             */
            $MerchantDetailRepository   = $this->Container->get(MerchantDetailRepository::class);
            $MerchantDetailEntity       = $MerchantDetailRepository->findOneByMerchantSeq($merchant_seq);
            if(!empty( $MerchantDetailEntity )){
                $detail                       = $MerchantDetailEntity->getData();
            }

            /**
             *@var RouterInterface $Router
             */
            if(!empty($detail['files'])){
                $Router = $this->Container->get(RouterInterface::class);
                foreach($detail['files'] AS $key => $file){
                    $fileid                     = $file['fileid'] ?? null;
                    if($fileid){
                        $detail['files'][$key]['url'] = $Router->generateUrl('image_read', ['fileid' => $fileid]);
                    }
                }
            }

            return $this->successJson("处理成功", $detail);
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function channel(int $seq = 0)
    {
        try{
            $merchant_seq       = $seq;
            $result             = [
                'channel'       =>[
                    'alipay'    => ['is_ok' => false, 'is_apply' => false, 'history' => []],
                    'wxpay'     => ['is_ok' => false, 'is_apply' => false, 'history' => []],
                ],
                'merchant'      => ['name' => '-'],
            ];

            /**
             *
             * @var MerchantRepository $MerchantRepository
             */
            $MerchantRepository             = $this->Container->get(MerchantRepository::class);
            $MerchantEntity                 = $MerchantRepository->findOneBySeq($merchant_seq);
            $result['merchant']['name']     = $MerchantEntity->getName();

            /**
             *
             * @var MerchantChannelRepository $MerchantChannelRepository
             * @var \asbamboo\qirifu\common\model\merchantChannel\Entity $MerchantChannelEntity
             * @var MerchantChannelLogRepository $MerchantChannelLogRepository
             * @var \asbamboo\qirifu\common\model\merchantChannelLog\Entity $MerchantChannelLogEntity
             */
            $MerchantChannelRepository      = $this->Container->get(MerchantChannelRepository::class);
            $MerchantChannelEntitys         = $MerchantChannelRepository->findAllByMerchantSeq($merchant_seq);
            $merchant_channel_seq_types     = [];
            $MerchantChannelLogEntitys      = [];
            if($MerchantChannelEntitys){
                foreach($MerchantChannelEntitys  AS $MerchantChannelEntity){
                    $type                                               = MerchantChannelCode::TYPE_NAMES[$MerchantChannelEntity->getType()];
                    $result['channel'][$type]['is_ok']                  = $MerchantChannelEntity->getStatus() == MerchantChannelCode::STATUS_OK;
                    $result['channel'][$type]['is_apply']               = $MerchantChannelEntity->getStatus() != MerchantChannelCode::STATUS_NO_APPLY;
                    $merchant_channel_seq                               = $MerchantChannelEntity->getSeq();
                    $merchant_channel_seq_types[$merchant_channel_seq]  = $type;
                }

            }

            if(!empty( $merchant_channel_seq_types )){
                $MerchantChannelLogRepository   = $this->Container->get(MerchantChannelLogRepository::class);
                $MerchantChannelLogEntitys      = $MerchantChannelLogRepository->findAllByMerchantChannelSeqs(array_keys($merchant_channel_seq_types));
            }

            if($MerchantChannelLogEntitys){
                foreach($MerchantChannelLogEntitys AS $MerchantChannelLogEntity){
                    $merchant_channel_seq           = $MerchantChannelLogEntity->getMerchantChannelSeq();
                    $type                           = $merchant_channel_seq_types[$merchant_channel_seq];
                    $result['channel'][$type]['history'][]     = [
                        'seq'                       => $MerchantChannelLogEntity->getSeq(),
                        'status'                    => $MerchantChannelLogEntity->getMerchantStatusLabel(),
                        'time'                      => date('Y-m-d H:i:s', $MerchantChannelLogEntity->getCreateTime()),
                    ];
                }
            }

            foreach($result['channel'] AS $type => $channel){
                if(empty($channel['history'])){
                    $result['channel'][$type]['history'][] = ['seq' => 0, 'status' => '商户未申请开通', 'time' => date('Y-m-d H:i:s')];
                }
            }

            return $this->successJson("处理成功", $result);
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function channelAvailableStatus(string $type)
    {
        try{
            $result = ['items' => []];
            if($type == "alipay"){
                foreach(MerchantChannelCode::STATUSS AS $merchant_channel_status => $lable){
                    if(MerchantChannelCode::STATUS_APPLY == $merchant_channel_status){
                        continue;
                    }else if(MerchantChannelCode::STATUS_NO_APPLY == $merchant_channel_status){
                        continue;
                    }else if(MerchantChannelCode::STATUS_REAPPLY == $merchant_channel_status){
                        continue;
                    }
                    $result['items'][]  = [
                        'key'           => $merchant_channel_status,
                        'status'        => MerchantChannelCode::STATUS_NAMES[$merchant_channel_status],
                        'label'         => $lable,
                    ];
                }
            }else if($type == "wxpay"){
                foreach(MerchantChannelCode::STATUSS AS $merchant_channel_status => $lable){
                    if(MerchantChannelCode::STATUS_APPLY == $merchant_channel_status){
                        continue;
                    }else if(MerchantChannelCode::STATUS_NO_APPLY == $merchant_channel_status){
                        continue;
                    }else if(MerchantChannelCode::STATUS_REAPPLY == $merchant_channel_status){
                        continue;
                    }else if(MerchantChannelCode::STATUS_WAIT_AUTHORIZATION == $merchant_channel_status){
                        continue;
                    }
                    $result['items'][]  = [
                        'key'           => $merchant_channel_status,
                        'status'        => MerchantChannelCode::STATUS_NAMES[$merchant_channel_status],
                        'label'         => $lable,
                    ];
                }
            }
            return $this->successJson("处理成功", $result);
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