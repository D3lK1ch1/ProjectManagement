<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\ActivityDependency;
use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $project = new Project();
        $project->setName('Mobile App Development Demo');

        $manager->persist($project);

        $activityDefs = [
            'A' => ['Requirements',     3.0],
            'B' => ['UI Design',        4.0],
            'C' => ['Backend API',      6.0],
            'D' => ['Database Design',  3.0],
            'E' => ['Frontend Dev',     5.0],
            'F' => ['Integration',      3.0],
            'G' => ['Testing',          4.0],
            'H' => ['Deployment',       2.0],
        ];

        $activities = [];
        foreach ($activityDefs as $key => [$name, $duration]) {
            $activity = new Activity();
            $activity->setName($name);
            $activity->setDuration($duration);
            $activity->setProject($project);
            $manager->persist($activity);
            $activities[$key] = $activity;
        }

        $dependencyDefs = [
            ['A', 'B'], ['A', 'C'], ['A', 'D'],
            ['B', 'E'],
            ['C', 'F'], ['E', 'F'],
            ['D', 'G'], ['F', 'G'],
            ['G', 'H'],
        ];

        foreach ($dependencyDefs as [$predKey, $succKey]) {
            $dep = new ActivityDependency();
            $dep->setPredecessor($activities[$predKey]);
            $dep->setSuccessor($activities[$succKey]);
            $manager->persist($dep);
        }

        $manager->flush();
    }
}
