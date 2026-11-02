<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class BundleTest extends KernelTestCase
{
    public function testBundleBootsAndRegistersTheDemoCommand(): void
    {
        $kernel = self::bootKernel();

        $command = new Application($kernel)->find('endroid:data-sanitize-demo:generate-data');
        self::assertSame([], $command->getDefinition()->getArguments());
    }
}
