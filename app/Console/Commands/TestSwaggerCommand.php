<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestSwaggerCommand extends Command
{
    protected $signature = 'test:swagger';
    protected $description = 'Test Swagger API documentation integration';

    public function handle()
    {
        $this->info("Testing Swagger API Documentation Integration");
        $this->info("=============================================");

        // Check if docs file exists
        $docsPath = storage_path('api-docs/api-docs.json');
        if (file_exists($docsPath)) {
            $this->info("✅ API documentation file exists: {$docsPath}");
            
            $docs = json_decode(file_get_contents($docsPath), true);
            $this->info("📋 API Title: " . $docs['info']['title']);
            $this->info("🏷️  API Version: " . $docs['info']['version']);
            $this->info("🌐 Server URL: " . $docs['servers'][0]['url']);
            $this->info("📊 Total Endpoints: " . count($docs['paths']));
            
            $this->newLine();
            $this->info("📝 Available Endpoints:");
            foreach ($docs['paths'] as $path => $methods) {
                foreach ($methods as $method => $details) {
                    $tag = $details['tags'][0] ?? 'Untagged';
                    $summary = $details['summary'] ?? 'No summary';
                    $this->line("  {$method} {$path} ({$tag}) - {$summary}");
                }
            }
        } else {
            $this->error("❌ API documentation file not found!");
            return 1;
        }

        $this->newLine();
        $this->info("🔗 Access Swagger UI at:");
        $this->line("   http://127.0.0.1:8000/api/documentation");
        
        $this->newLine();
        $this->info("🧪 Test API endpoints:");
        $this->line("   curl http://127.0.0.1:8000/api/public-test");
        
        $this->newLine();
        $this->warn("💡 To start the development server:");
        $this->line("   php artisan serve");

        return 0;
    }
}
