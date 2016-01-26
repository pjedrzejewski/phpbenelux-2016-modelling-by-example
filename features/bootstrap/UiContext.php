<?php

use App\Domain\Book;
use App\Domain\BookTitle;
use App\Domain\Isbn;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpKernel\KernelInterface;

class UiContext extends MinkContext implements KernelAwareContext, SnippetAcceptingContext
{
    private $library;
    private $entityManager;

    public function setKernel(KernelInterface $kernel)
    {
        $container = $kernel->getContainer();

        $this->library = $container->get('app.library');
        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @Given a book with ISBN :isbn and title :title was added to the library
     */
    public function aBookWithIsbnAndTitleWasAddedToTheCatalog($isbn, $title)
    {
        $this->library->add(Book::withTitleAndIsbn(new BookTitle($title), new Isbn($isbn)));
    }

    /**
     * @When I search library by ISBN number :isbn
     */
    public function iSearchCatalogByIsbnNumber($isbn)
    {
        $this->visit('/search');
        $this->fillField('ISBN', $isbn);
        $this->pressButton('Search');
    }

    /**
     * @Then I should find a single book
     */
    public function iShouldFindASingleBook()
    {
        $this->assertPageContainsText('Found 1 result(s).');
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
