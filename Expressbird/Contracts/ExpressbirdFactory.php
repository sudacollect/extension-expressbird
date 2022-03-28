<?php

namespace App\Extensions\Expressbird\Contracts;

interface ExpressbirdFactory
{
    // public function getDistance();

    // 发送
    public function send($order_id,$delivery_id,&$msg='');

    // 更新状态
    public function updateStatus($order_id,$params);

    // 取消发送
    public function cancelSend($order_id,$params,&$msg='');

    // 重新发送
    public function reSend($order_id,$delivery_id,&$msg='');

    // 获取骑手坐标
    public function riderLocation($order_id,&$msg='');

}