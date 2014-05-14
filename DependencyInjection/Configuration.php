<?php

namespace Wk\DhlApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wk_dhl_api');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $this->addB2bNode($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds the B2B configuration
     *
     * @param ArrayNodeDefinition $node
     */
    private function addB2bNode(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('b2b')
                    ->children()
                        ->arrayNode('accounts')
                            ->isRequired()
                            ->requiresAtLeastOneElement()
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('connection')
                            ->children()
                                ->scalarNode('wsdl_uri')
                                    ->cannotBeEmpty()
                                    ->defaultValue('https://cig.dhl.de/cig-wsdls/com/dpdhl/wsdl/geschaeftskundenversand-api/1.0/geschaeftskundenversand-api-1.0.wsdl')
                                ->end()
                                ->scalarNode('cis_base_uri')
                                    ->cannotBeEmpty()
                                    ->defaultValue('http://dhl.de/webservice/cisbase')
                                ->end()
                                ->arrayNode('intraship')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('user')
                                            ->cannotBeEmpty()
                                            ->defaultValue('geschaeftskunden_api')
                                        ->end()
                                        ->scalarNode('password')
                                            ->cannotBeEmpty()
                                            ->defaultValue('Dhl_ep_test1')
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('cig')
                                    ->children()
                                        ->scalarNode('end_point_uri')
                                            ->cannotBeEmpty()
                                            ->defaultValue('https://cig.dhl.de/services/sandbox/soap')
                                        ->end()
                                        ->scalarNode('user')
                                            ->isRequired()
                                            ->cannotBeEmpty()
                                        ->end()
                                        ->scalarNode('password')
                                            ->isRequired()
                                            ->cannotBeEmpty()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
