<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class TestApplicationsAPI extends Command
{
    protected $signature = 'test:applications-api 
                            {--all : Test all routes}
                            {--route= : Test specific route}
                            {--method=GET : HTTP method for specific route}
                            {--data= : JSON data for POST/PUT requests}
                            {--id=1 : ID for show/update/destroy routes}';
    
    protected $description = 'Test Applications Module API endpoints';

    public function handle()
    {
        if ($this->option('all')) {
            $this->testAllRoutes();
        } elseif ($this->option('route')) {
            $this->testSpecificRoute();
        } else {
            $this->listRoutes();
        }
    }

    protected function listRoutes()
    {
        $this->info('=== Applications Module API Routes ===');
        
        $routes = $this->getApplicationsRoutes();
        
        $tableData = $routes->map(function ($route) {
            return [
                'Method' => $this->formatMethods($route->methods),
                'URI' => $route->uri,
                'Name' => $route->getName() ?? 'N/A',
                'Action' => $route->getActionName(),
            ];
        })->toArray();

        $this->table(['Method', 'URI', 'Name', 'Action'], $tableData);
        
        $this->newLine();
        $this->info('Commands:');
        $this->line('  php artisan test:applications-api --all');
        $this->line('  php artisan test:applications-api --route=api/v1/applications');
        $this->line('  php artisan test:applications-api --route=api/v1/applications/1 --method=GET');
        $this->line('  php artisan test:applications-api --route=api/v1/applications --method=POST --data=\'{"name":"Test"}\'');
    }

    protected function getApplicationsRoutes()
    {
        return collect(Route::getRoutes()->getRoutes())
            ->filter(function ($route) {
                return str_contains($route->uri, 'applications') && 
                       str_contains($route->uri, 'api');
            })
            ->sortBy('uri');
    }

    protected function testAllRoutes()
    {
        $routes = $this->getApplicationsRoutes();
        $total = $routes->count();
        $success = 0;
        $failed = 0;

        $this->info("Testing {$total} Applications API routes...");
        $this->newLine();

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $results = [];

        foreach ($routes as $route) {
            try {
                $result = $this->executeRouteTest($route);
                $results[] = $result;
                
                if ($result['success']) {
                    $success++;
                } else {
                    $failed++;
                }
                
            } catch (\Exception $e) {
                $results[] = [
                    'route' => $route->uri,
                    'method' => $this->formatMethods($route->methods),
                    'status' => 'ERROR',
                    'message' => $e->getMessage(),
                    'success' => false,
                ];
                $failed++;
            }
            
            $progressBar->advance();
            usleep(100000); 
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info('=== Test Results ===');
        
        foreach ($results as $result) {
            $icon = $result['success'] ? '✓' : '✗';
            $color = $result['success'] ? 'green' : 'red';
            
            $this->line("<fg={$color}>{$icon} {$result['method']} {$result['route']} - {$result['status']}</>");
            
            if (!$result['success'] && !empty($result['message'])) {
                $this->line("  Message: {$result['message']}");
            }
        }

        $this->newLine();
        $this->info("Summary: {$success} passed, {$failed} failed out of {$total} routes");
    }

    protected function testSpecificRoute()
    {
        $routeUri = $this->option('route');
        $method = $this->option('method');
        $data = $this->option('data') ? json_decode($this->option('data'), true) : [];
        $id = $this->option('id');

        $this->info("Testing route: {$method} {$routeUri}");
        
        $uri = str_replace(['{application}', '{id}'], $id, $routeUri);

        try {
            $request = Request::create($uri, $method, $data);
            
            $request->headers->set('Accept', 'application/json');
            $request->headers->set('Content-Type', 'application/json');
            
            $this->info("Making request to: {$uri}");
            
            $response = app()->handle($request);
            
            $this->newLine();
            $this->info("=== Response ===");
            $this->line("Status Code: " . $response->getStatusCode());
            $this->line("Status Text: " . $response->getStatusCodeText());
            
            $content = $response->getContent();
            if ($content) {
                $this->line("Response Body:");
                
                if ($this->isJson($content)) {
                    $this->line(json_encode(json_decode($content), JSON_PRETTY_PRINT));
                } else {
                    $this->line($content);
                }
            }
            
            $this->newLine();
            $this->info("Response Headers:");
            foreach ($response->headers->all() as $name => $values) {
                $this->line("  {$name}: " . implode(', ', $values));
            }
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . ":" . $e->getLine());
            $this->error("Trace: " . $e->getTraceAsString());
        }
    }

    protected function executeRouteTest($route)
    {
        $primaryMethod = $this->getPrimaryMethod($route->methods);
        $uri = $route->uri;
        
        $uri = $this->prepareUriWithParameters($uri, $primaryMethod);
        
        $data = $this->getTestDataForMethod($primaryMethod);
        
        $request = Request::create($uri, $primaryMethod, $data);
        $request->headers->set('Accept', 'application/json');
        
        if (in_array($primaryMethod, ['POST', 'PUT', 'PATCH'])) {
            $request->headers->set('Content-Type', 'application/json');
        }
        
        $response = app()->handle($request);
        
        return [
            'route' => $route->uri,
            'method' => $primaryMethod,
            'status' => $response->getStatusCode(),
            'success' => $response->getStatusCode() >= 200 && $response->getStatusCode() < 400,
            'message' => $response->getStatusCodeText(),
        ];
    }

    protected function prepareUriWithParameters($uri, $method)
    {
        $replacements = [
            '{application}' => 1,
            '{id}' => 1,
            '{uuid}' => 'test-uuid',
            '{slug}' => 'test-slug',
        ];
        
        $uri = str_replace(array_keys($replacements), array_values($replacements), $uri);
        
        if ($method === 'GET' && str_contains($uri, '?')) {
            parse_str(parse_url($uri, PHP_URL_QUERY), $query);
            $uri = strtok($uri, '?') . '?' . http_build_query(array_merge($query, [
                'page' => 1,
                'per_page' => 10,
            ]));
        }
        
        return $uri;
    }

    protected function getTestDataForMethod($method)
    {
        $testData = [
            'name' => 'Test Application ' . time(),
            'description' => 'This is a test application created by API tester',
            'status' => 'active',
            'type' => 'web',
            'version' => '1.0.0',
        ];
        
        if ($method === 'POST') {
            return $testData;
        }
        
        if (in_array($method, ['PUT', 'PATCH'])) {
            return array_merge($testData, [
                '_method' => $method,
            ]);
        }
        
        return [];
    }

    protected function getPrimaryMethod($methods)
    {
        foreach ($methods as $method) {
            if ($method !== 'HEAD') {
                return $method;
            }
        }
        
        return 'GET';
    }

    protected function formatMethods($methods)
    {
        return implode('|', array_filter($methods, function ($method) {
            return $method !== 'HEAD';
        }));
    }

    protected function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}