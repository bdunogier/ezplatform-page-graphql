<?php
namespace spec\BD\EzPlatformGraphQLPage\GraphQL\Schema;

use EzSystems\EzPlatformGraphQL\Schema\Domain\Iterator;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\DomainIterator;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinition;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinitionFactory;
use PhpSpec\ObjectBehavior;

class DomainIteratorSpec extends ObjectBehavior
{
    function let(BlockDefinitionFactory $blockDefinitionFactory)
    {
        $this->beConstructedWith($blockDefinitionFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DomainIterator::class);
        $this->shouldHaveType(Iterator::class);
    }

    function it_yields_BlockDefinition_objects_from_configuration(BlockDefinitionFactory $blockDefinitionFactory)
    {
        $firstBlockDefinition = new BlockDefinition();
        $secondBlockDefinition = new BlockDefinition();

        $blockDefinitionFactory->getBlockIdentifiers()->willReturn(['first_block', 'second_block']);
        $blockDefinitionFactory->getBlockDefinition('first_block')->willReturn($firstBlockDefinition);
        $blockDefinitionFactory->getBlockDefinition('second_block')->willReturn($secondBlockDefinition);

        $this->iterate()->shouldYieldLike([
            ['BlockDefinition' => $firstBlockDefinition],
            ['BlockDefinition' => $secondBlockDefinition]
        ]);
    }

    function it_yields_BlockAttributeDefinition_objects_for_each_BlockDefinition(BlockDefinitionFactory $blockDefinitionFactory)
    {
        $firstBlockAttributeDefinition = new BlockAttributeDefinition();
        $secondBlockAttributeDefinition = new BlockAttributeDefinition();
        $blockDefinition = new BlockDefinition();
        $blockDefinition->setAttributes([$firstBlockAttributeDefinition, $secondBlockAttributeDefinition]);
        $blockDefinitionFactory->getBlockIdentifiers()->willReturn(['block']);
        $blockDefinitionFactory->getBlockDefinition('block')->willReturn($blockDefinition);

        $this->iterate()->shouldYieldLike([
            ['BlockDefinition' => $blockDefinition],
            ['BlockDefinition' => $blockDefinition, 'BlockAttributeDefinition' => $firstBlockAttributeDefinition],
            ['BlockDefinition' => $blockDefinition, 'BlockAttributeDefinition' => $secondBlockAttributeDefinition],
        ]);
    }

    function it_yields_views_for_each_BlockDefinition(BlockDefinitionFactory $blockDefinitionFactory)
    {
        $blockDefinition = new BlockDefinition();
        $blockDefinition->setViews([
            'default' => ['template' => 'ezdesign/blocks/test/default.html.twig', 'name' => 'Default'],
            'custom' => ['template' => 'ezdesign/blocks/test/custom.html.twig', 'name' => 'Custom']
        ]);
        $blockDefinitionFactory->getBlockIdentifiers()->willReturn(['block']);
        $blockDefinitionFactory->getBlockDefinition('block')->willReturn($blockDefinition);

        $this->iterate()->shouldYieldLike([
            ['BlockDefinition' => $blockDefinition],
            ['BlockDefinition' => $blockDefinition, 'BlockView' => ['identifier' => 'default', 'template' => 'ezdesign/blocks/test/default.html.twig', 'name' => 'Default']],
            ['BlockDefinition' => $blockDefinition, 'BlockView' => ['identifier' => 'custom', 'template' => 'ezdesign/blocks/test/custom.html.twig', 'name' => 'Custom']],
        ]);
    }
}
