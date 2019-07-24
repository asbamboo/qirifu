<?php
namespace asbamboo\qirifu\common\asbamboo\tradeCancel;

use asbamboo\qirifu\common\asbamboo\ApiClient;
use asbamboo\qirifu\common\exception\SystemException;

class TradeCancelRequest extends APiClient
{
    public $in_trade_no = '';
    public $out_trade_no = '';
    public $third_part = '';

    /**
     *
     * @throws SystemException
     * @return TradeCancelResponse
     */
    public function exec() : TradeCancelResponse
    {
        $respone            = $this->post([
            'api_name'      => 'trade.cancel',
            'in_trade_no'   => $this->in_trade_no,
            'out_trade_no'  => $this->out_trade_no,
            'third_part'    => $this->third_part,
        ]);

        if($respone['status'] != 'success'){
            throw new SystemException($respone['message']);
        }

        $TradeCancelResponse                    = new TradeCancelResponse();
        $TradeCancelResponse->in_trade_no        = $respone['decode_response']['data']['in_trade_no'] ?? null;
        $TradeCancelResponse->out_trade_no       = $respone['decode_response']['data']['out_trade_no'] ?? null;
        $TradeCancelResponse->channel            = $respone['decode_response']['data']['channel'] ?? null;
        $TradeCancelResponse->client_ip          = $respone['decode_response']['data']['client_ip'] ?? null;
        $TradeCancelResponse->title              = $respone['decode_response']['data']['title'] ?? null;
        $TradeCancelResponse->total_fee          = $respone['decode_response']['data']['total_fee'] ?? null;
        $TradeCancelResponse->trade_status       = $respone['decode_response']['data']['trade_status'] ?? null;
        $TradeCancelResponse->cancel_ymdhis      = $respone['decode_response']['data']['cancel_ymdhis'] ?? null;

        return $TradeCancelResponse;
    }
}