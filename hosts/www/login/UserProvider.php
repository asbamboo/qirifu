<?php
namespace asbamboo\qirifu\hosts\www\login;

use asbamboo\qirifu\common\login\UserProvider AS CommonLoginUserProvider;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\qirifu\common\model\user\Code AS UserCode;
use asbamboo\security\user\UserInterface;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月29日
 */
class UserProvider extends CommonLoginUserProvider
{
    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\qirifu\common\login\UserProvider::loadByLoginName()
     */
    public function loadByLoginName(string $login_name) : ? UserInterface
    {
        $User   = parent::loadByLoginName($login_name);
        if(empty($User)){
            throw new MessageException('用户不存在[User1]');
        }
        if($User->getType() != UserCode::TYPE_USER){
            throw new MessageException('用户不存在[User2]');
        }
        return $User;
    }
}