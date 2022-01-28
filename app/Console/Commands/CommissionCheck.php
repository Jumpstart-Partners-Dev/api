<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Cache;
use Session;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class CommissionCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    public $stores;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function searchLogin() {
        $this->line('Searching for login forms');
        $bar = $this->output->createProgressBar(count($this->stores));

        $bar->start();

        $bar->finish();

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->newLine();
        $this->line('Auto checking commission..');
        $this->line('Fetching list..');

        $this->stores = DB::table('store_commissions')->get();
        
        $this->line('Total of ' . count($this->stores) . ' records');
        
        $this->searchLogin();

        $this->newLine();
        return 0;
    }
}
