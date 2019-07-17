<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\qirifu\common\model\merchant\Manager AS MerchantManager;
use asbamboo\qirifu\common\model\merchant\Repository AS MerchantRepository;
use asbamboo\qirifu\common\model\merchantDetail\Repository AS MerchantDetailRepository;
use asbamboo\qirifu\common\model\merchantDetail\Manager AS MerchantDetailManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\router\RouterInterface;

class Merchant extends ControllerAbstract
{
    /**
     *
     * @return \asbamboo\http\ResponseInterface
     */
    public function getInfo()
    {
        try{
            $info   = [];

            /**
             * @var \asbamboo\qirifu\user\login\User $User
             * @var UserTokenInterface $UserToken
             * @var MerchantDetailRepository $MerchantDetailRepository
             */
            $UserToken                  = $this->Container->get(UserTokenInterface::class);
            $User                       = $UserToken->getUser();
            $MerchantDetailRepository   = $this->Container->get(MerchantDetailRepository::class);
            $MerchantDetailEntity       = $MerchantDetailRepository->findOneByUserId($User->getUserId());
            if(!empty( $MerchantDetailEntity )){
                $info                       = $MerchantDetailEntity->getData();
            }

            /**
             *@var RouterInterface $Router
             */
            if(!empty($info['files'])){
                $Router = $this->Container->get(RouterInterface::class);
                foreach($info['files'] AS $key => $file){
                    $fileid                     = $file['fileid'] ?? null;
                    if($fileid){
                        $info['files'][$key]['url'] = $Router->generateUrl('image_read', ['fileid' => $fileid]);
                    }
                }
            }

            return $this->json(['status' => 'success', 'data' => $info, 'editable' => true]);
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    /**
     *
     * @param ServerRequestInterface $Request
     * @return \asbamboo\http\ResponseInterface
     */
    public function save(ServerRequestInterface $Request)
    {
        try{
            $merchant_name          = trim($Request->getPostParam('name', ''));
            $merchant_link_man      = trim($Request->getPostParam('link_man', ''));
            $merchant_link_phone    = trim($Request->getPostParam('link_phone', ''));
            $merchant_detail_data   = (array) $Request->getPostParams() ?? [];

            /**
             *
             * @var \asbamboo\qirifu\user\login\User $User
             * @var UserTokenInterface $UserToken
             * @var MerchantManager $MerchantManager
             * @var MerchantRepository $MerchantRepository
             * @var MerchantDetailRepository $MerchantDetailRepository
             * @var MerchantDetailManager $MerchantDetailManager
             * @var DbFactoryInterface $Db
             */
            $UserToken                  = $this->Container->get(UserTokenInterface::class);
            $User                       = $UserToken->getUser();
            $Db                         = $this->Container->get(DbFactoryInterface::class);
            $MerchantManager            = $this->Container->get(MerchantManager::class);
            $MerchantRepository         = $this->Container->get(MerchantRepository::class);
            $MerchantDetailManager      = $this->Container->get(MerchantDetailManager::class);
            $MerchantDetailRepository   = $this->Container->get(MerchantDetailRepository::class);
            $MerchantEntity             = $MerchantRepository->findOneByUserId($User->getUserId());
            if(empty($MerchantEntity)){
                $Db->getManager()->transactional(function()use($User, $Db, $MerchantManager, $MerchantDetailManager, $merchant_name, $merchant_link_man, $merchant_link_phone, $merchant_detail_data){
                    $MerchantEntity = $MerchantManager->load();
                    $MerchantManager->create($User->getUserId(), $merchant_name, $merchant_link_man, $merchant_link_phone);
                    $Db->getManager()->flush();

                    $MerchantDetailManager->load();
                    $MerchantDetailManager->create($MerchantEntity, $merchant_detail_data);
                });
            }else{
                $MerchantManager->load($MerchantEntity);
                $MerchantManager->update($merchant_name, $merchant_link_man, $merchant_link_phone);

                $MerchantDetailEntity   = $MerchantDetailRepository->findOneByMerchantSeq($MerchantEntity->getSeq());
                $MerchantDetailManager->load($MerchantDetailEntity);
                $MerchantDetailManager->update($merchant_detail_data);

                $Db->getManager()->flush();
            }

            return $this->successJson('提交成功');
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