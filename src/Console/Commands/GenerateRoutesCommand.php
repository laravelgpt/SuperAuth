<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SuperAuth\Core\DynamicRouter;
use SuperAuth\Core\FeatureManager;

class GenerateRoutesCommand extends Command
{
    protected $signature = 'superauth:generate-routes 
                            {--feature= : Generate routes for specific feature}
                            {--output= : Output file path}
                            {--format=json : Output format (json, php, yaml)}';

    protected $description = 'Generate dynamic routes for SuperAuth package';

    protected DynamicRouter $router;
    protected FeatureManager $featureManager;

    public function __construct(DynamicRouter $router, FeatureManager $featureManager)
    {
        parent::__construct();
        $this->router = $router;
        $this->featureManager = $featureManager;
    }

    public function handle()
    {
        $this->info('🚀 Generating SuperAuth dynamic routes...');

        $feature = $this->option('feature');
        $output = $this->option('output');
        $format = $this->option('format');

        if ($feature) {
            $this->generateFeatureRoutes($feature, $output, $format);
        } else {
            $this->generateAllRoutes($output, $format);
        }

        $this->info('✅ Routes generated successfully!');
    }

    protected function generateFeatureRoutes(string $feature, ?string $output, string $format)
    {
        if (!$this->featureManager->isEnabled($feature)) {
            $this->error("Feature '{$feature}' is not enabled.");
            return;
        }

        $routes = $this->router->getFeatureRoutes($feature);
        
        if (empty($routes)) {
            $this->warn("No routes found for feature '{$feature}'.");
            return;
        }

        $this->info("Generating routes for feature: {$feature}");
        $this->displayRoutes($routes);
        
        if ($output) {
            $this->saveRoutes($routes, $output, $format);
        }
    }

    protected function generateAllRoutes(?string $output, string $format)
    {
        $allRoutes = $this->router->getRoutes();
        
        $this->info('Generating all routes...');
        
        foreach ($allRoutes as $feature => $routes) {
            if ($this->featureManager->isEnabled($feature)) {
                $this->line("  ✓ {$feature}: " . count($routes) . " routes");
            } else {
                $this->line("  ✗ {$feature}: disabled");
            }
        }

        if ($output) {
            $this->saveRoutes($allRoutes, $output, $format);
        }
    }

    protected function displayRoutes(array $routes)
    {
        $this->table(
            ['Method', 'Path', 'Name', 'Middleware'],
            collect($routes)->map(function ($route) {
                return [
                    strtoupper($route['method'] ?? 'GET'),
                    $route['path'] ?? '/',
                    $route['name'] ?? 'unnamed',
                    implode(', ', $route['middleware'] ?? [])
                ];
            })->toArray()
        );
    }

    protected function saveRoutes(array $routes, string $output, string $format)
    {
        $content = match ($format) {
            'json' => json_encode($routes, JSON_PRETTY_PRINT),
            'yaml' => $this->arrayToYaml($routes),
            'php' => $this->arrayToPhp($routes),
            default => throw new \InvalidArgumentException("Unsupported format: {$format}")
        };

        File::put($output, $content);
        $this->info("Routes saved to: {$output}");
    }

    protected function arrayToYaml(array $data): string
    {
        $yaml = '';
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $yaml .= "{$key}:\n";
                foreach ($value as $subKey => $subValue) {
                    $yaml .= "  {$subKey}: " . (is_array($subValue) ? json_encode($subValue) : $subValue) . "\n";
                }
            } else {
                $yaml .= "{$key}: {$value}\n";
            }
        }
        return $yaml;
    }

    protected function arrayToPhp(array $data): string
    {
        return "<?php\n\nreturn " . var_export($data, true) . ";\n";
    }
}
