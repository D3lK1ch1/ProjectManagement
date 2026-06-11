<?php

namespace App\Service;

class CriticalPathService
{
    public function calculate(array $activities, array $dependencies): array
    {
        $sorted = $this->topologicalSort($activities, $dependencies);
        $forward = $this->forwardPass($sorted, $activities, $dependencies);
        $backward = $this->backwardPass($sorted, $activities, $dependencies, $forward);
        return $this->buildResult($sorted, $forward, $backward);
    }

    private function topologicalSort(array $activities, array $dependencies): array
    {
        $inDegree = [];
        $adjList = [];
        $queue = [];
        $sorted = [];

        foreach ($activities as $activity) {
            $inDegree[$activity['id']] = 0;
        }

        foreach ($dependencies as $dep) {
            $predecessor = $dep['predecessor'];
            $successor = $dep['successor'];

            $adjList[$predecessor][] = $successor;
            $inDegree[$successor] = ($inDegree[$successor] ?? 0) + 1;
        }

        foreach ($inDegree as $node => $count) {
            if ($count === 0) {
                $queue[] = $node;
            }
        }

        while (!empty($queue)) {
            $node = array_shift($queue);
            $sorted[] = $node;
            foreach ($adjList[$node] ?? [] as $neighbor) {
                $inDegree[$neighbor]--;
                if ($inDegree[$neighbor] === 0) {
                    $queue[] = $neighbor;
                }
            }
        }

        return $sorted;
    }

    private function forwardPass(array $sorted, array $activities, array $dependencies): array
    {
        $es = [];
        $ef = [];
        $durations = [];

        foreach ($activities as $activity) {
            $durations[$activity['id']] = $activity['duration'];
        }

        foreach ($sorted as $id) {
            $predecessors = array_map(fn($d) => $d['predecessor'], array_filter($dependencies, fn($d) => $d['successor'] === $id));

            $es[$id] = $predecessors ? max(array_map(fn($p) => $ef[$p], $predecessors)) : 0;
            $ef[$id] = $es[$id] + $durations[$id];
        }

        return ['es' => $es, 'ef' => $ef];
    }

    private function backwardPass(array $sorted, array $activities, array $dependencies, array $forward): array
    {
        $ls = [];
        $lf = [];
        $durations = [];
        foreach ($activities as $activity) {
            $durations[$activity['id']] = $activity['duration'];
        }
        $projectDuration = max($forward['ef']);

        foreach (array_reverse($sorted) as $id) {
            $successors = array_map(fn($d) => $d['successor'], array_filter($dependencies, fn($d) => $d['predecessor'] === $id));

            $lf[$id] = $successors ? min(array_map(fn($s) => $ls[$s], $successors)) : $projectDuration;
            $ls[$id] = $lf[$id] - $durations[$id];
        }
        return ['ls' => $ls, 'lf' => $lf];
    }

    private function buildResult(array $sorted, array $forward, array $backward): array
    {
        $slack = [];
        $isCritical = [];
        $criticalPath = [];
        $activityDetails = [];

        foreach ($sorted as $id) {
            $slack[$id] = $backward['ls'][$id] - $forward['es'][$id];
            $isCritical[$id] = $slack[$id] == 0;

            if ($isCritical[$id]) {
                $criticalPath[] = $id;
            }

            $activityDetails[$id] = [
                'es' => $forward['es'][$id],
                'ef' => $forward['ef'][$id],
                'ls' => $backward['ls'][$id],
                'lf' => $backward['lf'][$id],
                'slack' => $slack[$id],
                'is_critical' => $isCritical[$id],
            ];
        }

        return ['project_duration' => max($forward['ef']), 'critical_path' => $criticalPath, 'activities' => $activityDetails];
    }
}
