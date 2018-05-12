<?php

namespace EricomGroup\SamanPaymentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
		$treeBuilder->root('saman_payment');
       $treeBuilder->root('saman_payment')
		->children()
			->scalarNode('merchant_id')->end()
			->scalarNode('password')->end()
		->end();

        return $treeBuilder;
    }
}
