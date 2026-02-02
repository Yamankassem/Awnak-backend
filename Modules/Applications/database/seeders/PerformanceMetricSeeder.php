<?php

namespace Modules\Applications\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerformanceMetricSeeder extends Seeder
{
    public function run(): void
    {
        $metrics = [];
        
        $availableMetrics = ['commitment', 'quality', 'collaboration', 'punctuality', 'initiative'];
        
        for ($feedbackId = 1; $feedbackId <= 10; $feedbackId++) {
            $numMetrics = rand(2, 4);
            $selectedMetrics = array_rand($availableMetrics, $numMetrics);
            
            if (!is_array($selectedMetrics)) {
                $selectedMetrics = [$selectedMetrics];
            }
            
            foreach ($selectedMetrics as $metricIndex) {
                $metricName = $availableMetrics[$metricIndex];
                $score = rand(1, 5);
                
                $metrics[] = [
                    'feedback_id' => $feedbackId,
                    'metric_name' => $metricName,
                    'score' => $score,
                    'notes' => $this->getRandomNotes($metricName, $score),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        DB::table('performance_metrics')->insert($metrics);
        
        $this->command->info(count($metrics) . ' performance metrics have been successfully created!');
    }
    
    private function getRandomNotes(string $metricName, int $score): string
    {
        $notesByMetric = [
            'commitment' => [
                1 => 'Weak commitment, needs significant improvement',
                2 => 'Limited commitment, needs development',
                3 => 'Average commitment, could be better',
                4 => 'Good and consistent commitment',
                5 => 'Excellent commitment, always present and enthusiastic'
            ],
            'quality' => [
                1 => 'Unacceptable work quality',
                2 => 'Below average work quality',
                3 => 'Average work quality',
                4 => 'Very good work quality',
                5 => 'Excellent and precise work quality'
            ],
            'collaboration' => [
                1 => 'Weak in teamwork',
                2 => 'Has difficulty cooperating',
                3 => 'Moderately cooperative',
                4 => 'Works well with the team',
                5 => 'Excellent collaborator and enhances team spirit'
            ],
            'punctuality' => [
                1 => 'Never punctual',
                2 => 'Often late',
                3 => 'Sometimes punctual',
                4 => 'Punctual',
                5 => 'Very punctual and always respects deadlines'
            ],
            'initiative' => [
                1 => 'Never takes initiative',
                2 => 'Rarely takes initiative',
                3 => 'Shows moderate initiative',
                4 => 'Takes good initiative',
                5 => 'Innovative and takes excellent initiative'
            ]
        ];
        
        return $notesByMetric[$metricName][$score] ?? 'No additional comments';
    }
}