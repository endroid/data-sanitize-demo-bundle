<?php

declare(strict_types=1);

namespace Endroid\DataSanitizeDemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'data_sanitize_demo_project')]
class Project implements \Stringable
{
    /** @var Collection<int, Task> */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Task::class)]
    public Collection $tasks;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'integer')]
        public readonly int $id,
        #[ORM\Column(type: 'string')]
        public string $name,
        #[ORM\Column(type: 'string')]
        public string $referenceId,
    ) {
        $this->tasks = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
