<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpKernel\KernelInterface;

class UiContext extends MinkContext implements KernelAwareContext, SnippetAcceptingContext
{
    private $entityManager;

    public function setKernel(KernelInterface $kernel)
    {
        $container = $kernel->getContainer();

        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @BeforeScenario
     */
    public function purgeDatabase(BeforeScenarioScope $scope)
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        $this->entityManager->clear();
    }
}
