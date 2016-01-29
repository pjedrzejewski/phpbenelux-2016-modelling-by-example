<?php

use App\Domain\Book;
use App\Domain\BookTitle;
use App\Domain\Isbn;
use App\Domain\SearchResults;
use App\Infrastructure\InMemoryLibrary;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;

class DomainContext implements Context, SnippetAcceptingContext
{
    private $library;
    private $currentBook;
    private $isbnToRemove;
    private $currentSearchResults;

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
     * @Given a book with ISBN :isbn and title :title was added to the library
     */
    public function aBookWithIsbnAndTitleWasAddedToTheLibrary($isbn, $title)
    {
        $this->currentBook = Book::withTitleAndIsbn(new BookTitle($title), new Isbn($isbn));
        $this->library->add($this->currentBook);
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
     * @When I try to remove it from the library
     */
    public function iTryToRemoveItFromTheLibrary()
    {
        $this->assertCurrentBookIsDefined();

        $this->library->remove($this->currentBook->isbn());
    }

    /**
     * @Then this book should no longer be in the library
     */
    public function thisBookShouldNoLongerBeInTheLibrary()
    {
        $this->assertCurrentBookIsDefined();

        expect($this->library->hasBookWithIsbn($this->currentBook->isbn()))->toBe(false);
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
            throw new \LogicException();
        }

        expect($this->library)->toThrow(new \InvalidArgumentException(sprintf('Book with ISBN "%s" does not exist!', $this->isbnToRemove)))->during('remove', array($this->isbnToRemove));
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

    private function assertCurrentBookIsDefined()
    {
        if (null === $this->currentBook) {
            throw new \LogicException('Current book must be defined!');
        }
    }
}
