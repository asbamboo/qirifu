<?php
namespace asbamboo\qirifu\common\listener;

use asbamboo\database\ManagerInterface;
use asbamboo\qirifu\common\model\sysDbLog\Manager AS SysDbLogManager;
use asbamboo\qirifu\common\model\sysDbLog\Entity AS SysDbLogEntity;
use asbamboo\http\ServerRequestInterface;
use asbamboo\database\FactoryInterface AS DatabaseFactoryInterface;
use asbamboo\di\ContainerInterface;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年12月18日
 */
class DatabaseListener
{
    /**
     *
     * @var ContainerInterface
     */
    private $Container;

    /**
     *
     * @param ContainerInterface $Container
     */
    public function __construct(ContainerInterface $Container)
    {
        $this->Container          = $Container;
    }

    /**
     *
     * @return SysDbLogManager
     */
    public function getSysDbLogManager() : SysDbLogManager
    {
        return $this->Container->get(SysDbLogManager::class);
    }

    /**
     *
     * @return ServerRequestInterface
     */
    public function getRequest() : ServerRequestInterface
    {
        return $this->Container->get(ServerRequestInterface::class);
    }

    /**
     *
     * @return DatabaseFactoryInterface
     */
    public function getDb() : DatabaseFactoryInterface
    {
        return $this->Container->get(DatabaseFactoryInterface::class);
    }

    /**
     * 监听database模块的datachange模块，写入数据变更日志
     *
     * @param string $type
     * @param ManagerInterface $DbManager
     * @param Object $Entity
     */
    public function createChangeLog(string $type, ManagerInterface $DbManager, Object $Entity) : void
    {
        if($Entity instanceof SysDbLogEntity){
            return;
        }
        $entity_class   = get_class($Entity);
        $ClassMetaData  = $DbManager->getClassMetadata($entity_class);
        $request_url    = substr($type . ':' . ($this->getRequest()->getServerParams()['HTTP_HOST'] ?? '') . (string) $this->getRequest()->getUri(), 0, 255);
        $table          = $ClassMetaData->getTableName();
        $uniqid         = implode('_', $ClassMetaData->getIdentifierValues($Entity));
        $data           = serialize($Entity);
        $request_info   = json_encode([
            'SERVER'    => $this->getRequest()->getServerParams(),
            'REQUEST'   => $this->getRequest()->getRequestParams(),
        ]);

        $SysDbLogEntity = $this->getSysDbLogManager()->load();

        $this->getSysDbLogManager()->create($request_url, $table, $uniqid, $data, $request_info);
    }
}
