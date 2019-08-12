<?php
namespace asbamboo\qirifu\common\asbamboo\tradePay;

use asbamboo\qirifu\common\asbamboo\ApiClient;
use asbamboo\qirifu\common\exception\SystemException;

class TradePayRequest extends APiClient
{
    public $channel = '';
    public $out_trade_no = '';
    public $title = '';
    public $total_fee = '';
    public $third_part = '';
    public $client_ip = '';
    public $notify_url = '';
    public $return_url = '';

    /**
     *
     * @throws SystemException
     * @return TradePayResponse
     */
    public function exec() : TradePayResponse
    {
        $respone            = $this->post([
            'api_name'      => 'trade.pay',
            'channel'       => $this->channel,
            'out_trade_no'  => $this->out_trade_no,
            'title'         => $this->title,
            'total_fee'     => $this->total_fee,
            'third_part'    => $this->third_part,
            'client_ip'     => $this->client_ip,
            'notify_url'    => $this->notify_url,
            'return_url'    => $this->return_url,
        ]);

        if($respone['status'] != 'success'){
            throw new SystemException($respone['message']);
        }

        $TradePayResponse                       = new TradePayResponse();
        $TradePayResponse->in_trade_no          = $respone['decode_response']['data']['in_trade_no'] ?? null;
        $TradePayResponse->out_trade_no         = $respone['decode_response']['data']['out_trade_no'] ?? null;
        $TradePayResponse->third_trade_no       = $respone['decode_response']['data']['third_trade_no'] ?? null;
        $TradePayResponse->channel              = $respone['decode_response']['data']['channel'] ?? null;
        $TradePayResponse->title                = $respone['decode_response']['data']['title'] ?? null;
        $TradePayResponse->total_fee            = $respone['decode_response']['data']['total_fee'] ?? null;
        $TradePayResponse->trade_status         = $respone['decode_response']['data']['trade_status'] ?? null;
        $TradePayResponse->qr_code              = $respone['decode_response']['data']['qr_code'] ?? null;
        $TradePayResponse->app_pay_json         = $respone['decode_response']['data']['app_pay_json'] ?? null;
        $TradePayResponse->onecd_pay_json       = $respone['decode_response']['data']['onecd_pay_json'] ?? null;
        $TradePayResponse->cancel_ymdhis        = $respone['decode_response']['data']['cancel_ymdhis'] ?? null;
        $TradePayResponse->client_ip            = $respone['decode_response']['data']['client_ip'] ?? null;
        $TradePayResponse->payok_ymdhis         = $respone['decode_response']['data']['payok_ymdhis'] ?? null;
        $TradePayResponse->payed_ymdhis         = $respone['decode_response']['data']['payed_ymdhis'] ?? null;

        return $TradePayResponse;
    }
}