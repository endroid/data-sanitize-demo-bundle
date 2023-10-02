<?php

declare(strict_types=1);

namespace Endroid\DataSanitizeDemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'data_sanitize_demo_task')]
class Task implements \Stringable
{
    /** @var Collection<int, Tag> */
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\JoinTable(name: 'data_sanitize_demo_task_tag')]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'tag_id', referencedColumnName: 'id')]
    public Collection $tags;

    /** @param array<Tag> $tags */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'integer')]
        public readonly int $id,
        #[ORM\Column(type: 'string')]
        public string $name,
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tasks')]
        public User $user,
        #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'tasks')]
        public Project $project,
        array $tags
    ) {
        $this->tags = new ArrayCollection($tags);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
