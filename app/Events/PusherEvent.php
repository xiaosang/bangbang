<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PusherEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
     public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     指定广播频道(对应前端的频道)
     */
    public function broadcastOn()
    {
        return new Channel('orders');
    }

    /**
     * 自定义广播名称(对应前端的名称)
     * @return string
     */
    public function broadcastAs()
    {
        return 'server.created';
    }

    /**
     * 控制你的广播数据
     */
    public function broadcastWith()
    {
        return $this->data;
    }
}
