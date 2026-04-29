<?php

namespace App\Tests\Service;

use App\Service\CriticalPathService;
use PHPUnit\Framework\TestCase;

class CriticalPathServiceTest extends TestCase
{
    public function testEightActivityExample(): void
    {
        $service = new CriticalPathService();
        $activities = [
            ['id' => 'A', 'duration' => 3],
            ['id' => 'B', 'duration' => 4],
            ['id' => 'C', 'duration' => 6],
            ['id' => 'D', 'duration' => 3],
            ['id' => 'E', 'duration' => 5],
            ['id' => 'F', 'duration' => 3],
            ['id' => 'G', 'duration' => 4],
            ['id' => 'H', 'duration' => 2],
        ];
        $dependencies = [
            ['predecessor' => 'A', 'successor' => 'B'],
            ['predecessor' => 'A', 'successor' => 'C'],
            ['predecessor' => 'A', 'successor' => 'D'],
            ['predecessor' => 'B', 'successor' => 'E'],
            ['predecessor' => 'C', 'successor' => 'F'],
            ['predecessor' => 'E', 'successor' => 'F'],
            ['predecessor' => 'D', 'successor' => 'G'],
            ['predecessor' => 'F', 'successor' => 'G'],
            ['predecessor' => 'G', 'successor' => 'H'],
        ];
        $result = $service->calculate($activities, $dependencies);

        $this->assertSame(21, $result['project_duration']);
        $this->assertSame(['A','B','E','F','G','H'], $result['critical_path']);
        $this->assertSame(3, $result['activities']['C']['slack']);
        $this->assertSame(9, $result['activities']['D']['slack']);
    }
}