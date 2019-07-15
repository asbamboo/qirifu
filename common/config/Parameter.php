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

    private $is_reset = false;

    /**
     * 类的实例
     * @var object
     */
    private static $instance;

    private function __construct()
    {
        $this->parameters   = json_decode(file_get_contents($this->getJsonPath()), true);
    }

    public static function instance() : self
    {
        if(! static::$instance){
            static::$instance    = new static();
        }
        return static::$instance;
    }

    public function get(string $key) /*: mixed*/
    {
        return $this->parameters[$key];
    }

    public function set(string $key, /*mixed*/ $value) : bool
    {
        if($this->parameters[$key] != $value){
            $this->parameters[$key] = $value;
            $this->is_reset   = true;
        }
        return true;
    }

    public function getJsonPath()
    {
        return dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'qirifu.json';
    }

    public function __destruct()
    {
        if($this->is_reset == true){
            file_put_contents($this->getJsonPath(), json_encode($this->parameters));
        }
    }
}
