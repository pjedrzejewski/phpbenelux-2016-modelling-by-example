<?php

use App\Domain\Book;
use App\Domain\BookTitle;
use App\Domain\Isbn;
use App\Infrastructure\InMemoryLibrary;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;

class DomainContext implements Context, SnippetAcceptingContext
{
    private $library;
    private $currentBook;

    public function __construct()
    {
        $this->library = new InMemoryLibrary();
    }

    /**
     * @Given I want to add a new book
     */
    public function iWantToAddANewBook()
    {
        $this->currentBook = null;
    }

    /**
     * @When I set the title to :title and ISBN to :isbn
     */
    public function iSetTheTitleToAndIsbnTo($title, $isbn)
    {
        $this->currentBook = Book::withTitleAndIsbn(new BookTitle($title), new Isbn($isbn));
    }

    /**
     * @When I try add this book to the library
     */
    public function iTryAddThisBookToTheLibrary()
    {
        $this->assertCurrentBookIsDefined();

        $this->library->add($this->currentBook);
    }

    /**
     * @Then this new book should be in the library
     */
    public function thisNewBookShouldBeInTheLibrary()
    {
        $this->assertCurrentBookIsDefined();

        expect($this->library->hasBookWithIsbn($this->currentBook->isbn()))->toBe(true);
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

    private function assertCurrentBookIsDefined()
    {
        if (null === $this->currentBook) {
            throw new \LogicException('Current book must be defined!');
        }
    }
}
