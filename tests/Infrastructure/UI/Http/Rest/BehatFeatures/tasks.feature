Feature:
  In order to test tasks functionality
  I want to...

  Scenario: I want to assign a task to an existing user
    Given I have a delivery company in the database with uuid:50000000-c555-c555-c555-000000000055 and name:Delivery Company 1
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000033 ,firstName:Pepe,lastName:Sanchez,userName:pepe980089 ,email:pepe@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "api/users/40000000-c222-c222-c222-000000000033/tasks" with body:
    """
    {
        "title": "new task",
        "priority": 52
    }
    """
    Then the response status code should be 201


  Scenario: I can't assign more that 3 tasks to the same user
    Given I have a delivery company in the database with uuid:50000000-c555-c555-c555-000000000055 and name:Delivery Company 1
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000033 ,firstName:Pepe,lastName:Sanchez,userName:pepe980089 ,email:pepe@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "api/users/40000000-c222-c222-c222-000000000033/tasks" with body:
    """
      {
          "title": "new task 1",
          "priority": 51
      }
    """
    Then the response status code should be 201
    Then I am going to:ADD THE SECOND TASK
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "api/users/40000000-c222-c222-c222-000000000033/tasks" with body:
    """
      {
          "title": "new task 2",
          "priority": 52
      }
    """
    Then the response status code should be 201
    Then I am going to:ADD THE THIRD TASK
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "api/users/40000000-c222-c222-c222-000000000033/tasks" with body:
    """
      {
          "title": "new task 3",
          "priority": 53
      }
    """
    Then the response status code should be 201
    Then I am going to:CHECK THE ERROR WHEN TRY TO ADD A FOURTH TASK
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "api/users/40000000-c222-c222-c222-000000000033/tasks" with body:
    """
      {
          "title": "new task 4",
          "priority": 54
      }
    """
    Then the response status code should be 409
    And the JSON should be equal to:
    """
    {
        "error": "max.number.of.tasks.reached"
    }
    """