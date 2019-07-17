<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\qirifu\common\model\merchant\Repository AS MerchantRepository;
use asbamboo\qirifu\common\model\merchantChannel\Code AS MerchantChannelCode;
use asbamboo\qirifu\common\model\merchantChannel\Repository AS MerchantChannelRepository;
use asbamboo\qirifu\common\model\merchantChannelLog\Repository AS MerchantChannelLogRepository;
use asbamboo\qirifu\common\model\merchantChannel\Manager AS MerchantChannelManager;
use asbamboo\qirifu\common\model\merchantChannelLog\Manager AS MerchantChannelLogManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\http\ServerRequestInterface;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2019年7月17日
 */
class Channel extends ControllerAbstract
{
    public function getInfo()
    {
        try{
            $result             = [
                'channel'       =>[
                    'alipay'    => ['is_apply' => false, 'status' => MerchantChannelCode::STATUS_NO_APPLY, 'history' => []],
                    'wxpay'     => ['is_apply' => false, 'status' => MerchantChannelCode::STATUS_NO_APPLY, 'history' => []],
                ],
            ];

            /**
             * @var \asbamboo\qirifu\user\login\User $User
             * @var UserTokenInterface $UserToken
             */
            $UserToken                  = $this->Container->get(UserTokenInterface::class);
            $User                       = $UserToken->getUser();

            /**
             *
             * @var MerchantChannelRepository $MerchantChannelRepository
             * @var \asbamboo\qirifu\common\model\merchantChannel\Entity $MerchantChannelEntity
             * @var MerchantChannelLogRepository $MerchantChannelLogRepository
             * @var \asbamboo\qirifu\common\model\merchantChannelLog\Entity $MerchantChannelLogEntity
             */
            $MerchantChannelRepository      = $this->Container->get(MerchantChannelRepository::class);
            $MerchantChannelEntitys         = $MerchantChannelRepository->findAllByUserId($User->getUserId());
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

    public function new(ServerRequestInterface $Request)
    {
        try{
            $merchant_channel_type_name = $Request->getPostParam('channel');
            if(!in_array($merchant_channel_type_name, MerchantChannelCode::TYPE_NAMES)){
                throw new MessageException('非法请求。[channel]');
            }
            $merchant_type              = array_search($merchant_channel_type_name, MerchantChannelCode::TYPE_NAMES);

            /**
             * @var \asbamboo\qirifu\user\login\User $User
             * @var UserTokenInterface $UserToken
             * @var MerchantRepository $MerchantRepository
             */
            $UserToken                  = $this->Container->get(UserTokenInterface::class);
            $User                       = $UserToken->getUser();
            $MerchantRepository         = $this->Container->get(MerchantRepository::class);
            $MerchantEntity             = $MerchantRepository->findOneByUserId($User->getUserId());

            if(empty($MerchantEntity)){
                throw new MessageException('请先提交商户资料。');
            }

            /**
             *
             * @var MerchantChannelManager $MerchantChannelManager
             * @var DbFactoryInterface $Db
             * @var MerchantChannelLogManager $MerchantChannelLogManager
             */
            $Db                         = $this->Container->get(DbFactoryInterface::class);
            $Db->getManager()->transactional(function()use($Db, $MerchantEntity, $merchant_type){
                $MerchantChannelManager     = $this->Container->get(MerchantChannelManager::class);
                $MerchantChannelEntity      = $MerchantChannelManager->load();
                $MerchantChannelManager->create($MerchantEntity, $merchant_type);
                $Db->getManager()->flush();
                $MerchantChannelLogManager  = $this->Container->get(MerchantChannelLogManager::class);
                $MerchantChannelLogManager->load();
                $MerchantChannelLogManager->create($MerchantChannelEntity, 'SYSTEM:商户申请开通支付渠道');
            });

            return $this->successJson('申请开通' . MerchantChannelCode::TYPES[$merchant_type] . "成功");
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