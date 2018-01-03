<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\DataSanitizeDemoBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EndroidDataSanitizeDemoExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // Do nothing
    }

    public function prepend(ContainerBuilder $container): void
    {
        // Add the package to the assets configuration so the correct manifest is used
        $container->prependExtensionConfig('framework', [
            'assets' => [
                'packages' => [
                    'endroid_data_sanitize_demo' => [
                        'json_manifest_path' => '%kernel.project_dir%/public/bundles/endroiddatasanitizedemo/build/manifest.json',
                    ],
                ],
            ],
        ]);
    }
}
