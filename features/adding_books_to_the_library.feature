Feature:
  In order to enrich our offer
  As a Librarian
  I want to add books to the library

  Scenario: Adding a book to the library
    Given I want to add a new book
    When I set the title to "Winds of Winter" and ISBN to "978-1-56619-909-4"
    And I try add this book to the library
    Then this new book should be in the library

  Scenario: Adding a book with existing ISBN number
    Given a book with ISBN "978-1-56619-909-4" and title "Winds of Winter" was added to the library
    When I try to create another book with the same ISBN number
    Then I should receive an error about non unique book
