<?php

declare(strict_types=1);

namespace Endroid\DataSanitizeDemoBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Endroid\DataSanitizeDemoBundle\Entity\Project;
use Endroid\DataSanitizeDemoBundle\Entity\Tag;
use Endroid\DataSanitizeDemoBundle\Entity\Task;
use Endroid\DataSanitizeDemoBundle\Entity\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'endroid:data-sanitize-demo:generate-data', description: 'Generate demo data')]
final class GenerateDataCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->truncateTables();

        $tagCount = 3;
        $projectCount = 8;
        $projectUserCount = 5;
        $projectUserTaskCount = 3;

        $tags = [];
        for ($tagId = 1; $tagId <= $tagCount; ++$tagId) {
            $tag = new Tag($tagId, 'Tag '.$tagId);
            $this->entityManager->persist($tag);
            $tags[$tagId] = $tag;
        }

        $userNumber = 1;
        $taskNumber = 1;
        for ($projectNumber = 1; $projectNumber <= $projectCount; ++$projectNumber) {
            $project = new Project($projectNumber, 'Project '.$projectNumber, chr(65 + $projectNumber));
            $this->entityManager->persist($project);
            for ($projectUserNumber = 1; $projectUserNumber <= $projectUserCount; ++$projectUserNumber) {
                $user = new User($userNumber, 'User '.$userNumber);
                $this->entityManager->persist($user);
                ++$userNumber;
                for ($projectUserTaskNumber = 1; $projectUserTaskNumber <= $projectUserTaskCount; ++$projectUserTaskNumber) {
                    $task = new Task($taskNumber, 'Task '.$taskNumber, $user, $project, $tags);
                    $this->entityManager->persist($task);
                    ++$taskNumber;
                }
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    private function truncateTables(): void
    {
        $tableNames = [
            'data_sanitize_demo_project_user',
            'data_sanitize_demo_task_tag',
            'data_sanitize_demo_project',
            'data_sanitize_demo_tag',
            'data_sanitize_demo_task',
            'data_sanitize_demo_user',
        ];

        foreach ($tableNames as $tableName) {
            $connection = $this->entityManager->getConnection();
            $platform = $connection->getDatabasePlatform();
            $query = $platform->getTruncateTableSql($tableName, true);
            $connection->executeStatement($query);
        }
    }
}
