<?php

declare(strict_types=1);

namespace Endroid\DataSanitizeDemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'data_sanitize_demo_user')]
class User implements \Stringable
{
    /** @var Collection<int, Project> */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'users', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'data_sanitize_example_user_project')]
    public Collection $projects;

    /** @var Collection<int, Task> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Task::class, cascade: ['persist'])]
    public Collection $tasks;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'integer')]
        public readonly int $id,
        #[ORM\Column(type: 'string')]
        public string $name,
    ) {
        $this->projects = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
