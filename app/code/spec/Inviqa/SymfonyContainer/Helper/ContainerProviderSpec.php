<?php

namespace spec;

use ContainerTools\Configuration;
use Inviqa_SymfonyContainer_Model_StoreConfigCompilerPass as StoreConfigCompilerPass;
use Inviqa_SymfonyContainer_Model_InjectableCompilerPass as InjectableCompilerPass;
use Symfony\Component\DependencyInjection\Container;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Inviqa_SymfonyContainer_Helper_ContainerProviderSpec extends ObjectBehavior
{
    function let(Configuration $generatorConfig, StoreConfigCompilerPass $configCompilerPass, InjectableCompilerPass $injectableCompilerPass)
    {
        $generatorConfig->getContainerFilePath()->willReturn('container.php');
        $generatorConfig->getDebug()->willReturn(true);
        $generatorConfig->getServicesFolders()->willReturn(['app/etc']);
        $generatorConfig->getServicesFormat()->willReturn('xml');
        $generatorConfig->getCompilerPasses()->willReturn([]);
        $generatorConfig->isTestEnvironment()->willReturn(false);

        $services = [
            'generatorConfig' => $generatorConfig,
            'storeConfigCompilerPass' => $configCompilerPass,
            'injectableCompilerPass' => $injectableCompilerPass
        ];

        $this->beConstructedWith($services);
    }

    function it_generates_container(Configuration $generatorConfig)
    {
        $generatorConfig->addCompilerPass(Argument::any())->shouldBeCalled();

        $this->getContainer()->shouldBeAnInstanceOf(Container::class);
    }

    function it_memoizes_container(Configuration $generatorConfig, StoreConfigCompilerPass $configCompilerPass, InjectableCompilerPass $injectableCompilerPass)
    {
        $generatorConfig->addCompilerPass($configCompilerPass)->shouldBeCalledTimes(1);
        $generatorConfig->addCompilerPass($injectableCompilerPass)->shouldBeCalledTimes(1);

        $container = $this->getContainer();
        $this->getContainer()->shouldBe($container);
    }
}
