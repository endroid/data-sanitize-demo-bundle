<?php

declare(strict_types=1);

namespace Endroid\DataSanitizeDemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'data_sanitize_demo_tag')]
class Tag implements \Stringable
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'integer')]
        public readonly int $id,
        #[ORM\Column(type: 'string')]
        public string $name
    ) {
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
