<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateLicense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:generate {--domain=} {--days=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate encrypted license file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domain = $this->option('domain') ?? request()->getHost();
        $expired = now()->addDays($this->option('days'))->toDateString();
        $secret = config('app.key'); // kunci rahasia aplikasi
        $raw = json_encode([
            'domain' => $domain,
            'expired' => $expired,
        ]);
        $encrypted = base64_encode(openssl_encrypt($raw, 'AES-256-CBC', $secret, 0, substr($secret, 0, 16)));

        file_put_contents(base_path('.license'), $encrypted);
        $this->info("License generated for {$domain}, expires at {$expired}");
    }
}
