<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\model\message\Repository AS MessageRepository;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\qirifu\common\model\messageDetail\Repository AS MessageDetailRepository;
use asbamboo\qirifu\common\model\message\Manager AS MessageManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;

class Message extends ControllerAbstract
{
    public function inbox(ServerRequestInterface $Request)
    {
        try{
            $data           = ['total' => 0, 'items'=>[]];

            /**
             *
             * @var MessageRepository $MessageRepository
             * @var MessageDetailRepository $MessageDetailRepository
             * @var UserTokenInterface $UserToken
             * @var \asbamboo\qirifu\common\model\message\Entity $MessageEntity
             */
            $MessageRepository          = $this->Container->get(MessageRepository::class);
            $MessageDetailRepository    = $this->Container->get(MessageDetailRepository::class);
            $UserToken                  = $this->Container->get(UserTokenInterface::class);
            $Pagintor                   = $MessageRepository->getPaginatorByWww($Request, $UserToken->getUser()->getUserId());
            $data['total']              = $Pagintor->count();
            foreach($Pagintor->getIterator() AS $MessageEntity){
                $data['items'][]        = [
                    'seq'               => $MessageEntity->getSeq(),
                    'title'             => $MessageEntity->getTitle(),
                    'is_read'           => $MessageEntity->getIsRead(),
                    'create_ymdhis'     => date('Y-m-d H:i:s', $MessageEntity->getCreateTime()),
                ];
            }
            $MessageDetailRepository->mappingContents($data['items']);


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


    public function inboxRead(ServerRequestInterface $Request)
    {
        try{
            $message_seq    = $Request->getRequestParam('seq');

            /**
             *
             * @var MessageRepository $MessageRepository
             * @var MessageManager $MessageManager
             * @var UserTokenInterface $UserToken
             * @var DbFactoryInterface $Db
             */
            $MessageRepository          = $this->Container->get(MessageRepository::class);
            $MessageManager             = $this->Container->get(MessageManager::class);
            $UserToken                  = $this->Container->get(UserTokenInterface::class);
            $Db                         = $this->Container->get(DbFactoryInterface::class);
            $MessageEntity              = $MessageRepository->findOneBySeq($message_seq);

            if($MessageEntity->getToUserId() != $UserToken->getUser()->getUserId()){
                throw new MessageException('非法操作');
            }

            $MessageManager->load($MessageEntity);
            $MessageManager->updateReaded();
            $Db->getManager()->flush();

            return $this->successJson("success");
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
