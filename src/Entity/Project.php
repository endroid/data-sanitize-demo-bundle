<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\DataSanitizeDemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="data_sanitize_demo_project")
 */
class Project
{
    /**
     * @ORM\Column(type="integer")
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="guid")
     */
    private $referenceId;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Endroid\DataSanitizeDemoBundle\Entity\Task", mappedBy="project", cascade={"persist"})
     */
    private $tasks;

    /**
     * @ORM\ManyToMany(targetEntity="Endroid\DataSanitizeDemoBundle\Entity\User", inversedBy="projects", cascade={"persist"})
     *
     * @ORM\JoinTable(
     *      name="data_sanitize_demo_project_user",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $users;

    public function __construct(int $id, string $referenceId, string $name)
    {
        $this->id = $id;
        $this->referenceId = $referenceId;
        $this->name = $name;

        $this->tasks = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTasks(): array
    {
        return $this->tasks->toArray();
    }

    public function setTasks(array $tasks): void
    {
        foreach ($this->tasks as $task) {
            if (!$this->hasTask($task)) {
                $this->removeTask($task);
            }
        }

        foreach ($tasks as $task) {
            $this->addTask($task);
        }
    }

    public function hasTask(Task $task): bool
    {
        return $this->tasks->contains($task);
    }

    public function addTask(Task $task): void
    {
        if (!$this->hasTask($task)) {
            $this->tasks->add($task);
            if ($task->getProject() !== $this) {
                $task->setProject($this);
            }
        }
    }

    public function removeTask(Task $task): void
    {
        if ($this->hasTask($task)) {
            $this->tasks->removeElement($task);
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }
    }

    public function getUsers(): array
    {
        return $this->users->toArray();
    }

    public function setUsers(array $users): void
    {
        foreach ($this->users as $user) {
            if (!$this->hasUser($user)) {
                $this->removeUser($user);
            }
        }

        foreach ($users as $user) {
            $this->addUser($user);
        }
    }

    public function hasUser(User $user): bool
    {
        return $this->users->contains($user);
    }

    public function addUser(User $user): void
    {
        if (!$this->hasUser($user)) {
            $this->users->add($user);
            if (!$user->hasProject($this)) {
                $user->addProject($this);
            }
        }
    }

    public function removeUser(User $user): void
    {
        if ($this->hasUser($user)) {
            $this->users->removeElement($user);
            if ($user->hasProject($this)) {
                $user->removeProject($this);
            }
        }
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
