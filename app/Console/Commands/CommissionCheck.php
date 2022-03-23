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

        $err = '';

        foreach($this->stores as $idx => $store) {
            if (filter_var($store->affiliate_url, FILTER_VALIDATE_URL)) {
                try {
                    $response = Http::get($store->affiliate_url);

                    $crawler = new Crawler($response->body());

                    $node = $crawler->selectButton('Log in');
                    if($node->text('empty') == 'empty') {
                        $node = $crawler->selectButton('Login');
                        if($node->text('empty') == 'empty') {
                            $node = $crawler->selectButton('LogIn');
                            if($node->text('empty') == 'empty') {
                                $node = $crawler->selectButton('LOGIN');
                                if($node->text('empty') == 'empty') {
                                    $node = $crawler->selectButton('Log In');
                                }
                            }
                        }
                    }

                    
                    if ($node->text('empty') != 'empty') {
                        $form = $node->form();

                        $uri = $form->getUri();
                        $method = $form->getMethod();
                        $name = $form->getName();
                        $values = $form->getValues();

                        DB::table('store_commissions')->where('id', $store->id)
                            ->update(['secure_url' => $uri, 'login_form' => json_encode($values), 'form_found' => 1]);

                    }
                } catch (\Throwable $th) {
                    DB::table('store_commissions')->where('id', $store->id)
                        ->update(['form_found' => 0]);
                    $err.= $store->id . ',';
                    // 19839
                }
            }

            

            $bar->advance();
        }

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

        $this->stores = DB::table('store_commissions')->where('form_found', 0)->orWhere('form_found', NULL)->orderBy('id', 'asc')->get();

        $this->line('Total of ' . count($this->stores) . ' records');
        
        $this->searchLogin();

        $this->newLine();
        return 0;
    }
}
