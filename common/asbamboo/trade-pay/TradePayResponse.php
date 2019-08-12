<?php
namespace asbamboo\qirifu\common\asbamboo\tradePay;

class TradePayResponse
{
    public $in_trade_no;
    public $out_trade_no;
    public $third_trade_no;
    public $channel;
    public $title;
    public $total_fee;
    public $trade_status;
    public $qr_code;
    public $app_pay_json;
    public $onecd_pay_json;
    public $cancel_ymdhis;
    public $client_ip;
    public $payok_ymdhis;
    public $payed_ymdhis;

}