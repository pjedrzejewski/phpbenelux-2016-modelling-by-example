<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

class DomainContext implements Context, SnippetAcceptingContext
{

    /**
     * @Given I want to add a new book
     */
    public function iWantToAddANewBook()
    {
        throw new PendingException();
    }

    /**
     * @When I set the title to :arg1 and ISBN to :arg2
     */
    public function iSetTheTitleToAndIsbnTo($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @When I try add this book to the library
     */
    public function iTryAddThisBookToTheLibrary()
    {
        throw new PendingException();
    }

    /**
     * @Then this new book should be in the library
     */
    public function thisNewBookShouldBeInTheLibrary()
    {
        throw new PendingException();
    }

    /**
     * @Given a book with ISBN :arg1 and title :arg2 was added to the library
     */
    public function aBookWithIsbnAndTitleWasAddedToTheLibrary($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @When I try to create another book with the same ISBN number
     */
    public function iTryToCreateAnotherBookWithTheSameIsbnNumber()
    {
        throw new PendingException();
    }

    /**
     * @Then I should receive an error about non unique book
     */
    public function iShouldReceiveAnErrorAboutNonUniqueBook()
    {
        throw new PendingException();
    }

    /**
     * @When I try to remove it from the library
     */
    public function iTryToRemoveItFromTheLibrary()
    {
        throw new PendingException();
    }

    /**
     * @Then this book should no longer be in the library
     */
    public function thisBookShouldNoLongerBeInTheLibrary()
    {
        throw new PendingException();
    }

    /**
     * @When I try to remove book with ISBN :arg1
     */
    public function iTryToRemoveBookWithIsbn($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should receive an error about non-existent book
     */
    public function iShouldReceiveAnErrorAboutNonExistentBook()
    {
        throw new PendingException();
    }

    /**
     * @When I search library by ISBN number :arg1
     */
    public function iSearchLibraryByIsbnNumber($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should find a single book
     */
    public function iShouldFindASingleBook()
    {
        throw new PendingException();
    }
}
