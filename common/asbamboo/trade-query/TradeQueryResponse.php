<?php
namespace asbamboo\qirifu\common\asbamboo\tradeQuery;

class TradeQueryResponse
{
    public $in_trade_no;
    public $out_trade_no;
    public $third_trade_no;
    public $channel;
    public $client_ip;
    public $title;
    public $total_fee;
    public $trade_status;
    public $app_pay_json;
    public $onecd_pay_json;
    public $qr_code;
    public $payok_ymdhis;
    public $payed_ymdhis;
    public $cancel_ymdhis;
}