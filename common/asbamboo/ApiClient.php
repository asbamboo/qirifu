<?php
namespace asbamboo\qirifu\common\asbamboo;

use asbamboo\qirifu\common\exception\SystemException;

class ApiClient
{
    use SignTrait;

    const MODES = [
//         ['label' => '官方开发', 'value' => 'ROOTDEV'],
        ['label' => '开发环境', 'value' => 'DEV'],
        ['label' => '正式环境', 'value' => 'PRD'],
    ];

    private $app_key;

    private $app_serect;

    private $run_mode='ROOTDEV';

    public function __construct()
    {
        $this->app_key      = \Parameter::instance()->get('ASBAMBOO_APPKEY');
        $this->app_serect   = \Parameter::instance()->get('ASBAMBOO_APPSERECT');
        $this->run_mode     = \Parameter::instance()->get('ASBAMBOO_MODE');
    }

    public function post(array $assign_data)
    {
        $assign_data    = $this->filterAssignData($assign_data);
        $curl           = $this->_curl($assign_data);
        if( $curl['curl_errno'] ){
            throw new SystemException("curlerror:{$curl['curl_errno']},{$curl['curl_error']}");
        }

        if( $curl['curl_info']['http_code'] < 200 || $curl['curl_info']['http_code'] > 299 ){
            throw new SystemException("Asbamboo 接口请求异常:http_code[{$curl['curl_info']['http_code']}]");
        }

        $status				= 'failed';
        $decode_response	= json_decode( $curl['response'], TRUE );
        if(!$this->checkSign($decode_response, $this->app_serect)){
            throw new SystemException("Asbamboo 接口请求异常:响应值的签名无效");
        }
        if( isset($decode_response['code']) && $decode_response['code'] == 'SUCCESS' ){
                $status	= "success";
        }
        return [
            'status' 			=> $status,
            'message' 			=> $decode_response['message'],
            'decode_response'	=> $decode_response,
            'response'			=> $curl['response'],
        ];
    }

    /**
     * 接口请求url
     * @return string
     */
    public function getUrl() : string
    {
        $url    = 'http://api.asbamboo.com';
        if($this->run_mode == 'DEV'){
            $url    = 'http://developer.asbamboo.com/api';
        }else if($this->run_mode == 'ROOTDEV'){
            $url    = 'http://api.asbamboo.de';
        }
        return $url;
    }

    /**
     * 请求参数添加app_key参数
     * 请求参数添加sign参数
     *
     * @param array $assign_data
     */
    public function filterAssignData(array $assign_data) : array
    {
        unset($assign_data['sign']);
        $assign_data['app_key']     = $this->app_key;
        $assign_data['timestamp']   = date('Y-m-d H:i:s');
        $assign_data['version']     = 'v1.0';
        $assign_data['sign']        = $this->makeSign($assign_data, $this->app_serect);
        return $assign_data;
    }

    /**
     *
     * 发出curl 请求
     *
     * @param array $assign_data
     * @return array
     */
    private function _curl(array $assign_data) : array
    {
        $ch	= curl_init();  // 启动一个CURL会话
        curl_setopt($ch, CURLOPT_URL, $this->getUrl()); // 要访问的地址
        curl_setopt($ch, CURLOPT_POST, true);			// 发送一个常规的Post请求
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($assign_data));	// Post提交的数据包
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);			// 设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_HEADER, FALSE);			// 显示返回的Header区域内容
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);	// 获取的信息以文件流的形式返回
        $response	= curl_exec($ch); // 执行操作
        $curl_info	= curl_getinfo($ch);
        $curl_errno	= curl_errno($ch);
        $curl_error	= curl_error($ch);
        curl_close($ch); // 关闭CURL会话

        return[
            'curl_info'		=> $curl_info,
            'curl_errno'	=> $curl_errno,
            'curl_error'	=> $curl_error,
            'response'		=> $response,
        ];
    }
}