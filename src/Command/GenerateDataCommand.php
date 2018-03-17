<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\DataSanitizeDemoBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Endroid\DataSanitizeDemoBundle\Entity\Project;
use Endroid\DataSanitizeDemoBundle\Entity\Tag;
use Endroid\DataSanitizeDemoBundle\Entity\Task;
use Endroid\DataSanitizeDemoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateDataCommand extends ContainerAwareCommand
{
    private $entityManager;

    public function __construct(?string $name = null, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('endroid:data-sanitize-demo:generate-data')
            ->setDescription('Generate demo data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTables();

        $projectCount = 8;
        $projectUserCount = 5;
        $userTaskCount = 3;
        $tagCount = 3;

        $currentUser = 1;
        $currentTask = 1;

        $tags = [];
        for ($t = 1; $t <= $tagCount; ++$t) {
            $tag = new Tag();
            $tag->setName('Tag '.$t);
            $tags[$t] = $tag;
        }

        for ($p = 1; $p <= $projectCount; ++$p) {
            $project = new Project();
            $project->setName('Project '.$p);
            for ($u = 1; $u <= $projectUserCount; ++$u) {
                $user = new User();
                $user->setName('User '.$currentUser);
                for ($t = 1; $t <= $userTaskCount; ++$t) {
                    $task = new Task();
                    $task->setName('Task '.$currentTask);
                    $task->setTags($tags);
                    $user->addTask($task);
                    $project->addTask($task);
                    ++$currentTask;
                }
                ++$currentUser;
                $project->addUser($user);
            }
            $this->entityManager->persist($project);
        }

        $this->entityManager->flush();
    }

    private function truncateTables(): void
    {
        $tableNames = [
            'data_sanitize_demo_project',
            'data_sanitize_demo_project_user',
            'data_sanitize_demo_tag',
            'data_sanitize_demo_task',
            'data_sanitize_demo_task_tag',
            'data_sanitize_demo_user',
        ];

        foreach ($tableNames as $tableName) {
            $connection = $this->entityManager->getConnection();
            $platform = $connection->getDatabasePlatform();
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $query = $platform->getTruncateTableSql($tableName);
            $connection->executeUpdate($query);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
