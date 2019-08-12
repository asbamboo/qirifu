<?php
namespace asbamboo\qirifu\common\asbamboo\tradePay;

use asbamboo\qirifu\common\asbamboo\ApiClient;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\asbamboo\SignTrait;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\qirifu\common\exception\SystemException;

class TradePayNotify extends APiClient
{
    use SignTrait;

    public $channel;
    public $in_trade_no;
    public $title;
    public $out_trade_no;
    public $third_trade_no;
    public $total_fee;
    public $client_ip;

    /**
     * NOPAY[尚未支付] CANCLE[取消支付] PAYFAILED[支付失败] PAYING[正在支付] PAYOK[支付成功-可退款] PAYED[支付成功-不可退款]
     *
     * @var string
     */
    public $trade_status;
    public $payok_ymdhis;
    public $payed_ymdhis;
    public $cancel_ymdhis;
    public $random;

    public function __construct(ServerRequestInterface $Request)
    {
        $this->validateSign($Request);
        $this->setProperty($Request);
    }

    /**
     *
     * @param ServerRequestInterface $Request
     * @throws SystemException
     */
    public function validateSign(ServerRequestInterface $Request)
    {
        $app_serect             = \Parameter::instance()->get('ASBAMBOO_APPSERECT');
        $app_serect_wxowner     = \Parameter::instance()->get('WXPAY_OWNER_ASBAMBOO_APPSERECT');
        if(!$this->checkSign($Request->getRequestParams(), $app_serect) && !$this->checkSign($Request->getRequestParams(), $app_serect_wxowner)){
            throw new SystemException('asbamboo返回值中签名无效');
        }
    }

    /**
     *
     * @param ServerRequestInterface $Request
     */
    public function setProperty(ServerRequestInterface $Request)
    {
        $this->channel          = $Request->getRequestParam('channel');
        $this->in_trade_no      = $Request->getRequestParam('in_trade_no');
        $this->title            = $Request->getRequestParam('title');
        $this->out_trade_no     = $Request->getRequestParam('out_trade_no');
        $this->third_trade_no   = $Request->getRequestParam('third_trade_no');
        $this->total_fee        = $Request->getRequestParam('total_fee');
        $this->client_ip        = $Request->getRequestParam('client_ip');
        $this->trade_status     = $Request->getRequestParam('trade_status');
        $this->payok_ymdhis     = $Request->getRequestParam('payok_ymdhis');
        $this->payed_ymdhis     = $Request->getRequestParam('payed_ymdhis');
        $this->cancel_ymdhis    = $Request->getRequestParam('cancel_ymdhis');
    }
}