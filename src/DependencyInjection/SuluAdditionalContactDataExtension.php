<?php

namespace Manuxi\SuluAdditionalContactDataBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

class SuluAdditionalContactDataExtension extends Extension implements PrependExtensionInterface
{

    public function prepend(ContainerBuilder $container)
    {
        if($container->hasExtension("sulu_admin")){
            $container->prependExtensionConfig(
                "sulu_admin",
                [
                    "forms" => [
                        "directories" => [
                            __DIR__ . "/../../config/forms"
                        ]
                    ],
                    "resources" => [
                        "additional_contact_data" => [
                            "routes" => [
                                "detail" => 'sulu_additional_contact_data.get_additional-contact-data'
                            ]
                        ]
                    ]
                ]
            );
        }
        if($container->hasExtension("sulu_contact")){
            $container->prependExtensionConfig(
                "sulu_contact",
                [
                    "objects" => [
                        "contact" => [
                            "model" => 'Manuxi\SuluAdditionalContactDataBundle\Entity\Contact'
                        ]
                    ]
                ]
            );
        }

        $container->loadFromExtension('framework', [
            'default_locale' => 'en',
            'translator' => ['paths' => [__DIR__ . '/../../translations/']],
        ]);

    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . "/../../config"));
        $loader->load("services.xml");

/*        $loaderYaml = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . "/../../config"));
        $loaderYaml->load("routes_admin.yml");*/
    }

}