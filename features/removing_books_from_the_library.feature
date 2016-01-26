Feature:
  In order to keep our offer up to date
  As a Librarian
  I want to be able to remove books from the library

  Scenario: Removing a book from the library
    Given a book with ISBN "978-1-56619-909-4" and title "Winds of Winter" was added to the library
    When I try to remove it from the library
    Then this book should no longer be in the library

  Scenario: Trying to remove a non-existent book
    Given a book with ISBN "978-1-56619-909-4" and title "Winds of Winter" was added to the library
    When I try to remove book with ISBN "978-0618640157"
    Then I should receive an error about non-existent book
