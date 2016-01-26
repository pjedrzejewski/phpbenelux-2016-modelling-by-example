Feature:
  In order to find a specific book
  As a Customer
  I want to search books by ISBN

  @critical
  Scenario: Searching books by ISBN number
    Given a book with ISBN "978-1-56619-909-4" and title "Winds of Winter" was added to the library
    When I search library by ISBN number "978-1-56619-909-4"
    Then I should find a single book
