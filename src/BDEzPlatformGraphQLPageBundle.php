<?php
namespace BD\EzPlatformGraphQLPage;

use BD\EzPlatformGraphQLPage\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BDEzPlatformGraphQLPageBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container); // TODO: Change the autogenerated stub
        
        $container->addCompilerPass(new Compiler\RegisterBlocksAttributesTypesPass());
        $container->addCompilerPass(new Compiler\RegisterBlocksTypesPass());
        $container->addCompilerPass(new Compiler\PageResolverBlocksTypesPass());
    }
}