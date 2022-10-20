Feature:
  In order to test delivery companies functionality, I want to...

  Scenario: Given delivery companies in the system I want to retrieve the list of them
    Given I have a delivery company in the database with uuid:50000000-c555-c555-c555-000000000055 and name:Delivery Company 1
    And I have a delivery company in the database with uuid:50000000-c555-c555-c555-000000000066 and name:Delivery Company 2
    And I send a "GET" request to "api/deliverycompanies"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    [
        {
            "uuid": "50000000-c555-c555-c555-000000000055",
            "name": "Delivery Company 1",
            "tasksInTodo": 0,
            "vipLevel": "BASE"
        },
        {
            "uuid": "50000000-c555-c555-c555-000000000066",
            "name": "Delivery Company 2",
            "tasksInTodo": 0,
            "vipLevel": "BASE"
        }
    ]
    """


  Scenario: When a user add a new tasks, delivery company increase its number of todo tasks
    Given I have a delivery company in the database with uuid:50000000-c555-c555-c555-000000000055 and name:Delivery Company 1
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000033 ,firstName:Pepe,lastName:Sanchez,userName:pepe980089 ,email:pepe@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "api/users/40000000-c222-c222-c222-000000000033/tasks" with body:
    """
      {
          "uuid": "22000000-c222-c222-c222-000000000022",
          "title": "new task",
          "priority": 52
      }
    """
    Then the response status code should be 201
    Then emulated the async processing of the TaskCreatedEvent for user with uuid:40000000-c222-c222-c222-000000000033 and task with uuid:22000000-c222-c222-c222-000000000022
    Then I am going to:CHECK DELIVERY COMPANY HAS INCREASED ITS NUMBER OF TODO TASKS
    And I send a "GET" request to "api/deliverycompanies"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    [
        {
            "uuid": "50000000-c555-c555-c555-000000000055",
            "name": "Delivery Company 1",
            "tasksInTodo": 1,
            "vipLevel": "BASE"
        }
    ]
    """
