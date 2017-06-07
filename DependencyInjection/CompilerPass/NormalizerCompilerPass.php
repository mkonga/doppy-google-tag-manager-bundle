<?php

namespace Doppy\GoogleTagManagerBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NormalizerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder)
    {
        // find all tagged normalizers
        $taggedNormalizers = $this->getTaggedNormalizers($containerBuilder);

        // only do something if something is found
        if (count($taggedNormalizers) > 0) {
            // get serializer
            $serializerDefinition = $containerBuilder->findDefinition('doppy_google_tag_manager.serializer');

            // get current normalizers
            $normalizers = $serializerDefinition->getArgument(0);

            // add the normalizers we found
            foreach ($taggedNormalizers as $taggedNormalizer) {
                $normalizers[] = new Reference($taggedNormalizer);
            }

            // set the new argument
            $serializerDefinition->setArgument(0, $normalizers);
        }
    }

    /**
     * @param ContainerBuilder $containerBuilder
     *
     * @return array
     */
    protected function getTaggedNormalizers(ContainerBuilder $containerBuilder)
    {
        $services = array();
        foreach ($containerBuilder->findTaggedServiceIds('doppy_google_tag_manager.normalizer') as $serviceId => $tags) {
            foreach ($tags as $attributes) {
                $services[] = $serviceId;
            }
        }
        return $services;
    }
}
