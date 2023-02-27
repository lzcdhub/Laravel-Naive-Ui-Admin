<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestSendMsgCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendMsg';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送消息';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo 1;
    }
}
