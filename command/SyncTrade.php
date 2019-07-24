<?php
namespace asbamboo\qirifu\command;

use asbamboo\console\command\CommandAbstract;
use asbamboo\di\ContainerAwareTrait;
use asbamboo\console\ProcessorInterface;
use asbamboo\qirifu\common\model\trade\Repository AS TradeRepository;
use asbamboo\qirifu\common\model\trade\Code AS TradeCode;
use asbamboo\qirifu\common\helper\TradeHelper;

/**
 * api 处理程序 trade.pay接口对应的notify_url上异步通知的程序
 *  - 如果异步通知推送失败，那么通过这个命令行程序能重新推送
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2019年3月17日
 */
class SyncTrade extends CommandAbstract
{
    use ContainerAwareTrait;

    public function __construct()
    {
        parent::__construct();
        $this->AddOption('ymdhis', null, '在这个时间之前的未支付且未取消的订单状态同步。');
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\console\command\CommandInterface::exec()
     */
    public function exec(ProcessorInterface $Processor)
    {
        try{
            $ymdhis             = $this->getOptionValueByProcessor('ymdhis', $Processor);
            $max_create_time    = empty($ymdhis) ? time() : strtotime($ymdhis);

            $Processor->output()->println('处理trade.pay失败的notify消息重发，开始。');
            $Processor->output()->println('now:' . date('Y-m-d H:i:s'));
            $Processor->output()->println('Ymdhis:' . date('Y-m-d H:i:s', $max_create_time));
            $Processor->output()->println('===================================================================');

            /**
             *
             * @var TradeRepository $TradeRepository
             * @var TradeHelper $TradeHelper
             */
            $TradeRepository    = $this->Container->get(TradeRepository::class);
            $TradeHelper        = $this->Container->get(TradeHelper::class);

            /**
             *
             * @var \asbamboo\webapp\common\model\apiNotifyLog\Entity $ApiNotifyLogEntity
             */
            $limit              = 100;
            $limit_start_seq    = 0;
            $loop               = 0;
            $loop_limit         = 999; //未防止死循环做一个循环次数限制
            while($loop < $loop_limit){
                $offset     = 0; // 每次都从第0个开始因为，每次循环过后会处理一批数据
                $loop++;
                $Processor->output()->println('$offset:' . $offset);
                $Processor->output()->println('$limit:' . $limit);

                $qirifu_trade_nos   = $TradeRepository->noPayQirifuTradeNos($max_create_time, $limit, $limit_start_seq);
                if(!empty($qirifu_trade_nos)){
                    foreach($qirifu_trade_nos AS $trade_seq => $qirifu_trade_no){
                        try{
                            $Processor->output()->println('******************************************************************');
                            $Processor->output()->println('now sycnc qirifu_trade_no:' . $qirifu_trade_no);


                            $TradeEntity    = $TradeHelper->syncStatus($qirifu_trade_no);

                            $Processor->output()->println('now sycnc success: [status:' . TradeCode::STATUSS[$TradeEntity->getStatus()] . ']');

                        } catch (\Throwable $e) {
                            $Processor->output()->println('now sycnc error:' . (string) $e);
                        }

                        if($trade_seq > $limit_start_seq){
                            $limit_start_seq    = $trade_seq;
                        }
                    }
                }else{
                    break;
                }
                $Processor->output()->println('-------------------------------------------------------------------');
            }

            /**
             * 响应结果
             */
            $Processor->output()->println('===================================================================');
            $Processor->output()->println('同步支付信息完成。');
        }catch(\asbamboo\qirifu\common\exception\MessageException $e){
            $Processor->output()->println($e->getMessage());
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\console\command\CommandInterface::help()
     */
    public function help(): string
    {
        $console    = $_SERVER['SCRIPT_FILENAME'];
        return <<<HELP
    把未确定支付状态的支付信息做信息同步，包含以下未支付状态的订单(NOPAY,PAYING,PAYFAILED)。
    NOPAY与PAYING状态的超过一定时间未支付的交易取消。支付失败状态的交易直接取消。
    NOPAY与PAYING状态的同步时发现已经支付的，数据状态修改为已支付。
    例:
        php {$console} {$this->getName()}
        php {$console} {$this->getName()} --ymdhis="2018-09-09 10:13:00"
HELP;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\console\command\CommandInterface::desc()
     */
    public function desc(): string
    {
        return "同步支付信息";
    }

    /**
     *
     * @return string
     */
    public function getName() : string
    {
        return 'asbamboo:qirifu:sync-trade';
    }
}