<?php

use App\Domain\Book;
use App\Domain\BookTitle;
use App\Domain\Isbn;
use App\Domain\SearchResults;
use App\Infrastructure\InMemoryLibrary;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

class DomainContext implements Context, SnippetAcceptingContext
{
    private $library;
    private $currentBook;
    private $currentSearchResults;
    private $isbnToRemove;

    public function __construct()
    {
        $this->library = new InMemoryLibrary();
        $this->currentSearchResults = SearchResults::asEmpty();
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
    public function iTryAddThisBookToTheCatalog()
    {
        $this->assertCurrentBookIsDefined();

        $this->library->add($this->currentBook);
    }

    /**
     * @Then this new book should be in the library
     */
    public function thisNewBookShouldBeInTheCatalog()
    {
        $this->assertCurrentBookIsDefined();

        if (false === $this->library->hasBookWithIsbn($this->currentBook->isbn())) {
            throw new \Exception('Book is not in the library...');
        }
    }

    /**
     * @Given a book with ISBN :isbn and title :title was added to the library
     */
    public function aBookWithIsbnAndTitleWasAddedToTheCatalog($isbn, $title)
    {
        $this->currentBook = Book::withTitleAndIsbn(new BookTitle($title), new Isbn($isbn));
        $this->library->add($this->currentBook);
    }

    /**
     * @When I try to remove it from the library
     */
    public function iTryToRemoveItFromTheCatalog()
    {
        if (null === $this->currentBook) {
            throw new \LogicException('First create a book, before trying to remove it!');
        }
        $this->library->remove($this->currentBook->isbn());
    }

    /**
     * @Then this book should no longer be in the library
     */
    public function thisBookShouldNoLongerBeInTheCatalog()
    {
        $this->assertCurrentBookIsDefined();
        if (true === $this->library->hasBookWithIsbn($this->currentBook->isbn())) {
            throw new \Exception('Book is still in the library...');
        }
    }

    /**
     * @When I search library by ISBN number :isbn
     */
    public function iSearchCatalogByIsbnNumber($isbn)
    {
        $this->currentSearchResults = $this->library->searchByIsbn(new Isbn($isbn));
    }

    /**
     * @Then I should find a single book
     */
    public function iShouldFindASingleBook()
    {
        if (1 !== $actualCount = $this->currentSearchResults->count()) {
            throw new \Exception(sprintf('Expected exactly 1 book, but got %d...', $actualCount));
        }
    }

    /**
     * @When I try to create another book with the same ISBN number
     */
    public function iTryToCreateAnotherBookWithTheSameIsbnNumber()
    {
        $this->assertCurrentBookIsDefined();

        $this->currentBook = Book::withTitleAndIsbn(new BookTitle('Random Title'), $this->currentBook->isbn());
    }

    /**
     * @Then I should receive an error about non unique book
     */
    public function iShouldReceiveAnErrorAboutNonUniqueBook()
    {
        $this->assertCurrentBookIsDefined();

        expect($this->library)->toThrow(\InvalidArgumentException::class)->during('add', array($this->currentBook));
    }

    /**
     * @When I try to remove book with ISBN :isbn
     */
    public function iTryToRemoveBookWithIsbn($isbn)
    {
        $this->isbnToRemove = new Isbn($isbn);
    }

    /**
     * @Then I should receive an error about non-existent book
     */
    public function iShouldReceiveAnErrorAboutNonExistentBook()
    {
        if (null === $this->isbnToRemove) {
            throw new \LogicException('ISBN to remove must be defined!');
        }

        expect($this->library)->toThrow(\InvalidArgumentException::class)->during('remove', array($this->isbnToRemove));
    }

    private function assertCurrentBookIsDefined()
    {
        if (null === $this->currentBook) {
            throw new \LogicException('Current book must be defined!');
        }
    }
}
