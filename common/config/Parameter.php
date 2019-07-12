<?php
class Parameter
{
    /******************************************************************************
     * 系统变量
     ******************************************************************************
     * SYSTEM_NAME       = '七日付';
     ******************************************************************************
     *
     ******************************************************************************
     * 数据库
     ******************************************************************************
     * DB_DRIVER         = 'pdo_mysql';
     * DB_HOST           = '127.0.0.1';
     * DB_NAME           = 'qirifu';
     * DB_USER           = 'root';
     * DB_PASSWORD       = 'root';
     * DB_CHARSET        = 'utf8mb4';
     ******************************************************************************
     *
     ******************************************************************************
     * 系统发送邮件的email账号
     ******************************************************************************
     * MAILER_HOST       = 'smtp.aliyun.com';
     * MAILER_PORT       = '465';
     * MAILER_ENCRYPTION = 'ssl';
     * MAILER_USER       = 'qirifu@aliyun.com';
     * MAILER_PASSWORD   = 'qirifu123456';
     ******************************************************************************
     *
     * @var array
     */
    private $parameters;

    /**
     * 类的实例
     * @var object
     */
    private static $instance;

    private function __construct()
    {
        $this->parameters   = json_decode(file_get_contents(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'qirifu.json'), true);
    }

    public static function instance() : self
    {
        if(! static::$instance){
            static::$instance    = new static();
        }
        return static::$instance;
    }

    public function get(string $key)
    {
        return $this->parameters[$key];
    }
}
