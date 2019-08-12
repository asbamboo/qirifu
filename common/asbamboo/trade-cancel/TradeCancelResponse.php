<?php
namespace asbamboo\qirifu\common\asbamboo\tradeCancel;

class TradeCancelResponse
{
    public $in_trade_no;
    public $out_trade_no;
    public $third_trade_no;
    public $channel;
    public $client_ip;
    public $title;
    public $total_fee;
    public $trade_status;
    public $cancel_ymdhis;
}