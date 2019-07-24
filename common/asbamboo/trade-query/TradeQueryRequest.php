<?php
namespace asbamboo\qirifu\common\asbamboo\tradeQuery;

use asbamboo\qirifu\common\asbamboo\ApiClient;
use asbamboo\qirifu\common\exception\SystemException;

class TradeQueryRequest extends APiClient
{
    public $in_trade_no = '';
    public $out_trade_no = '';
    public $third_part = '';

    /**
     *
     * @throws SystemException
     * @return TradeQueryResponse
     */
    public function exec() : TradeQueryResponse
    {
        $respone            = $this->post([
            'api_name'      => 'trade.query',
            'in_trade_no'   => $this->in_trade_no,
            'out_trade_no'  => $this->out_trade_no,
            'third_part'    => $this->third_part,
        ]);

        if($respone['status'] != 'success'){
            throw new SystemException($respone['message']);
        }

        $TradeQueryResponse                     = new TradeQueryResponse();
        $TradeQueryResponse->in_trade_no        = $respone['decode_response']['data']['in_trade_no'] ?? null;
        $TradeQueryResponse->out_trade_no       = $respone['decode_response']['data']['out_trade_no'] ?? null;
        $TradeQueryResponse->channel            = $respone['decode_response']['data']['channel'] ?? null;
        $TradeQueryResponse->client_ip          = $respone['decode_response']['data']['client_ip'] ?? null;
        $TradeQueryResponse->title              = $respone['decode_response']['data']['title'] ?? null;
        $TradeQueryResponse->total_fee          = $respone['decode_response']['data']['total_fee'] ?? null;
        $TradeQueryResponse->trade_status       = $respone['decode_response']['data']['trade_status'] ?? null;
        $TradeQueryResponse->app_pay_json       = $respone['decode_response']['data']['app_pay_json'] ?? null;
        $TradeQueryResponse->onecd_pay_json     = $respone['decode_response']['data']['onecd_pay_json'] ?? null;
        $TradeQueryResponse->qr_code            = $respone['decode_response']['data']['qr_code'] ?? null;
        $TradeQueryResponse->payok_ymdhis       = $respone['decode_response']['data']['payok_ymdhis'] ?? null;
        $TradeQueryResponse->payed_ymdhis       = $respone['decode_response']['data']['payed_ymdhis'] ?? null;
        $TradeQueryResponse->cancel_ymdhis      = $respone['decode_response']['data']['cancel_ymdhis'] ?? null;

        return $TradeQueryResponse;
    }
}