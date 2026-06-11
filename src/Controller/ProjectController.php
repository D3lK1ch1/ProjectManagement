<?php

namespace App\Controller;

use App\Repository\ActivityDependencyRepository;
use App\Repository\ActivityRepository;
use App\Repository\ProjectRepository;
use App\Service\CriticalPathService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{

    public function __construct(
        private ProjectRepository $projectRepo,
        private ActivityRepository $activityRepo,
        private ActivityDependencyRepository $dependencyRepo,
        private CriticalPathService $criticalPathService,
    ) {}

    #[Route('/api/projects', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $projects = $this->projectRepo->findAll();
        $data = array_map(fn($p) => ['id' => $p->getId(), 'name' => $p->getName()], $projects);
        return new JsonResponse($data);
    }

    #[Route('/api/projects/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $project = $this->projectRepo->find($id);

        if (!$project) {
            return new JsonResponse(['error' => 'Project not found'], 404);
        }

        $data = ['id' => $project->getId(), 'name' => $project->getName()];

        return new JsonResponse($data);
    }

    #[Route('/api/projects/{id}/critical-path', methods: ['GET'])]
    public function criticalPath(int $id): JsonResponse
    {
        $project = $this->projectRepo->find($id);
        if (!$project) {
            return new JsonResponse(['error' => 'Project not found'], 404);
        }

        $activities = $this->activityRepo->findBy(['project' => $project]);
        $activityIds = array_map(fn($a) => $a->getId(), $activities);
        $dependencyEntities = $this->dependencyRepo->findBy(['predecessor' => $activityIds]);

        $activityData = array_map(fn($a) => ['id' => $a->getId(), 'duration' => $a->getDuration()], $activities);
        $dependencyData = array_map(fn($d) => ['predecessor' => $d->getPredecessor()->getId(), 'successor' => $d->getSuccessor()->getId()], $dependencyEntities);

        $result = $this->criticalPathService->calculate($activityData, $dependencyData);

        return new JsonResponse($result);
    }

    #[Route('/api/projects/{id}/network-diagram', methods: ['GET'])]
    public function networkDiagram(int $id): JsonResponse
    {
        $project = $this->projectRepo->find($id);
        if (!$project) {
            return new JsonResponse(['error' => 'Project not found'], 404);
        }

        $activities = $this->activityRepo->findBy(['project' => $project]);
        $activityIds = array_map(fn($a) => $a->getId(), $activities);
        $dependencyEntities = $this->dependencyRepo->findBy(['predecessor' => $activityIds]);

        $activityMap = [];
        foreach ($activities as $a) {
            $activityMap[$a->getId()] = $a;
        }

        $activityData = array_map(fn($a) => ['id' => $a->getId(), 'duration' => $a->getDuration()], $activities);
        $dependencyData = array_map(fn($d) => ['predecessor' => $d->getPredecessor()->getId(), 'successor' => $d->getSuccessor()->getId()], $dependencyEntities);

        $result = $this->criticalPathService->calculate($activityData, $dependencyData);

        $nodes = [];
        foreach ($activities as $a) {
            $calc = $result['activities'][$a->getId()];
            $nodes[] = [
                'id'          => $a->getId(),
                'label'       => $a->getName() . ' (' . (int)$a->getDuration() . 'd)',
                'duration'    => $a->getDuration(),
                'es'          => $calc['es'],
                'ef'          => $calc['ef'],
                'ls'          => $calc['ls'],
                'lf'          => $calc['lf'],
                'slack'       => $calc['slack'],
                'is_critical' => $calc['is_critical'],
            ];
        }

        $edges = [];
        foreach ($dependencyEntities as $d) {
            $predId = $d->getPredecessor()->getId();
            $succId = $d->getSuccessor()->getId();
            $edges[] = [
                'from'        => $predId,
                'to'          => $succId,
                'is_critical' => $result['activities'][$predId]['is_critical'] && $result['activities'][$succId]['is_critical'],
            ];
        }

        $mermaid = "graph LR\n";
        foreach ($edges as $e) {
            $predLabel = $activityMap[$e['from']]->getName() . ' (' . (int)$activityMap[$e['from']]->getDuration() . 'd)';
            $succLabel = $activityMap[$e['to']]->getName() . ' (' . (int)$activityMap[$e['to']]->getDuration() . 'd)';
            $predNode = $e['from'] . '["' . $predLabel . '"]';
            $succNode = $e['to'] . '["' . $succLabel . '"]';
            $arrow = $e['is_critical'] ? ':::critical --> ' : ' --> ';
            $mermaid .= '    ' . $predNode . $arrow . $succNode . "\n";
        }
        $criticalIds = implode(',', $result['critical_path']);
        if ($criticalIds) {
            $mermaid .= '    classDef critical fill:#ff6b6b,color:#fff,stroke:#c0392b' . "\n";
            foreach ($result['critical_path'] as $critId) {
                $mermaid .= '    class ' . $critId . ' critical' . "\n";
            }
        }

        return new JsonResponse([
            'nodes'            => $nodes,
            'edges'            => $edges,
            'project_duration' => $result['project_duration'],
            'critical_path'    => $result['critical_path'],
            'mermaid'          => $mermaid,
        ]);
    }
}
