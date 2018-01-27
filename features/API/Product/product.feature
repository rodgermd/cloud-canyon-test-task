Feature: Product
  In order to work with products
  I am able to list products and create new product

  Scenario: List products
    When I send a GET request to "/product"
    Then the response code should be 405
    And the response should contain "Method Not Allowed"
    When I send a GET request to "/products"
    Then the response code should be 400
    When I send a GET request to "/products" with query parameters:
    """
      limit=1
    """

    Then the response code should be 200
    And the JSON array contains 1 elements
    And the JSON at "0" should have the following:
      | name       |
      | type       |
      | color      |
      | texture    |
      | width      |
      | height     |
      | created_at |
      | updated_at |
    And the JSON at "0" should have the following:
      | name | "product ONE 0" |
      | type | 1               |
      | id   | 1               |
    When I send a GET request to "/products" with query parameters:
    """
      limit=2
    """
    Then the response code should be 200
    And the JSON array contains 2 elements
    And the JSON at "1" should have the following:
      | id | 2 |
    When I send a GET request to "/products" with query parameters:
    """
      limit=1
      offset=1
    """
    Then the response code should be 200
    And the JSON array contains 1 elements
    And the JSON at "0" should have the following:
      | id | 2 |

  Scenario: Add product
    Given I empty products table
    When I send a GET request to "/products" with query parameters:
    """
      limit=1
    """
    Then the response code should be 200
    And the JSON array contains 0 elements
    When I send a POST request to "/product"
    Then the response code should be 400
    When I send a POST request to "/product" with form data:
    """
      name=test name 2
      type=1
      texture=some
    """
    Then the response code should be 400
    When I send a POST request to "/product" with form data:
    """
      name=test name 2
      type=2
      height=100
    """
    Then the response code should be 400
    When I send a POST request to "/product" with form data:
    """
      name=test name
      type=1
      color=red
      texture=some
    """
    Then the response code should be 201
    And the JSON should have the following:
      | id      | 1           |
      | name    | "test name" |
      | color   | "red"       |
      | texture | "some"      |
      | height  | null        |
      | width   | null        |
    When I send a POST request to "/product" with form data:
    """
      name=test name
      type=2
      height=400
      width=500
    """
    Then the response code should be 201
    And the JSON should have the following:
      | id     | 2           |
      | name   | "test name" |
      | height | 400         |
      | width  | 500         |
