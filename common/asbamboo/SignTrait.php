<?php
namespace asbamboo\qirifu\common\asbamboo;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2019年7月22日
 */
trait SignTrait
{
    /**
     * 生成签名
     *
     * @param array $assign_data
     * @param string $app_serect
     * @return string
     */
    public function makeSign(array $assign_data, string $app_serect) : string
    {
        ksort($assign_data);
        $sign_data  = [];
        foreach($assign_data AS $key => $value){
            $sign_data[]    = $key . $value;
        }
        $sign_data[]    = $app_serect;
        $sign_data  = implode('', $sign_data);
        return strtoupper(md5($sign_data));
    }

    /**
     *
     * @param array $decoded_response
     * @param string $app_serect
     * @return bool
     */
    public function checkSign(array $decoded_response, string $app_serect) : bool
    {
        $sign   = $decoded_response['sign'] ?? '';
        unset($decoded_response['sign']);
        ksort($decoded_response);
        $sign_data  = [];
        foreach($decoded_response AS $key => $value){
            if($key == 'data'){
                ksort($value);
                $inner_sign_data    = [];
                foreach($value AS $k => $v){
                    $inner_sign_data[]  = $k . $v;
                }
                $inner_sign_data    = implode('', $inner_sign_data);
                $sign_data[]        = $key.$inner_sign_data;
            }else{
                $sign_data[]    = $key.$value;
            }
        }
        $sign_data[]    = $app_serect;
        $sign_data      = implode('', $sign_data);
        $check_sign     = md5($sign_data);
        return strtolower($check_sign) == strtolower($sign);
    }
}