<?php

namespace App\Console\Commands;

use App\Models\Link;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

/**
 * @class CheckLinks
 */
class CheckLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'links:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client();
        Link::query()->chunk(
            50, 
            function ($links) use ($client) {
                foreach ($links as $link) {
                    try {
                        $response = $client->get($link->link, ['connect_timeout' => 5]);

                        if ($response->getStatusCode() !== 200) {
                            $link->status = Link::STATUS_BROKEN;
                        } else {
                            $link->status = LINK::STATUS_WORKING;
                        }
                    } catch (GuzzleException $e) {
                        $link->status = Link::STATUS_BROKEN;
                    }
                    
                    $this->info($link->link . ' status is ' . $link->status);

                    $link->save();
                }
            }
        );
    }
}
