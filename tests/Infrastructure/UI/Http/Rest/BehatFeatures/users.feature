Feature:
  In order to test users functionality
  I want to...

  Scenario: I want to save a new user that belongs to a previous existing Delivery Company
    Given I have a delivery company in the database with uuid:50000000-c555-c555-c555-000000000055 and name:Delivery Company 1
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000033 ,firstName:Pepe,lastName:Sanchez,userName:pepe980089 ,email:pepe@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    When I add "Content-Type" header equal to "application/json"
    And I send a "POST" request to "api/users" with body:
    """
      {
          "deliverycompanyuuid": "50000000-c555-c555-c555-000000000055",
          "useruuid": "40000000-c222-c222-c222-000000000044",
          "firstname": "John",
          "lastname": "Doe",
          "email": "johndow@4a-side.ninja",
          "username": "superjohn35"
      }
    """
    Then the response status code should be 201


  Scenario: Given users in the system I want to retrieve the list of them
    Given I have a delivery company in the database with uuid:50000000-c555-c555-c555-000000000055 and name:Delivery Company 1
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000033 ,firstName:Pepe,lastName:Sanchez,userName:pepe980089 ,email:pepe@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000044 ,firstName:Juan,lastName:Ortega,userName:ortigosa25 ,email:jjjyeah@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000055 ,firstName:Susana,lastName:Tomares,userName:susimares ,email:suto59@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    When I add "Content-Type" header equal to "application/json"
    And I send a "GET" request to "api/users"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    [
        {
            "deliveryCompanyUuid": "50000000-c555-c555-c555-000000000055",
            "uuid": "40000000-c222-c222-c222-000000000033",
            "firstname": "Pepe",
            "lastname": "Sanchez",
            "username": "pepe980089",
            "email": "pepe@4a-side.ninja"
        },
        {
            "deliveryCompanyUuid": "50000000-c555-c555-c555-000000000055",
            "uuid": "40000000-c222-c222-c222-000000000044",
            "firstname": "Juan",
            "lastname": "Ortega",
            "username": "ortigosa25",
            "email": "jjjyeah@4a-side.ninja"
        },
        {
            "deliveryCompanyUuid": "50000000-c555-c555-c555-000000000055",
            "uuid": "40000000-c222-c222-c222-000000000055",
            "firstname": "Susana",
            "lastname": "Tomares",
            "username": "susimares",
            "email": "suto59@4a-side.ninja"
        }
    ]
    """

  Scenario: Given users in the system I want to retrieve the list of them including delivery companies names
    Given I have a delivery company in the database with uuid:50000000-c555-c555-c555-000000000055 and name:Delivery Company 1
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000033 ,firstName:Pepe,lastName:Sanchez,userName:pepe980089 ,email:pepe@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000044 ,firstName:Juan,lastName:Ortega,userName:ortigosa25 ,email:jjjyeah@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000055 ,firstName:Susana,lastName:Tomares,userName:susimares ,email:suto59@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    When I add "Content-Type" header equal to "application/json"
    And I send a "GET" request to "api/usersdcnames"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    [
        {
            "deliveryCompany": "Delivery Company 1",
            "uuid": "40000000-c222-c222-c222-000000000033",
            "firstname": "Pepe",
            "lastname": "Sanchez",
            "email": "pepe@4a-side.ninja",
            "username": "pepe980089"
        },
        {
            "deliveryCompany": "Delivery Company 1",
            "uuid": "40000000-c222-c222-c222-000000000044",
            "firstname": "Juan",
            "lastname": "Ortega",
            "email": "jjjyeah@4a-side.ninja",
            "username": "ortigosa25"
        },
        {
            "deliveryCompany": "Delivery Company 1",
            "uuid": "40000000-c222-c222-c222-000000000055",
            "firstname": "Susana",
            "lastname": "Tomares",
            "email": "suto59@4a-side.ninja",
            "username": "susimares"
        }
    ]
    """

  Scenario: Given users in the system I want delete one of them
    Given I have a delivery company in the database with uuid:50000000-c555-c555-c555-000000000055 and name:Delivery Company 1
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000033 ,firstName:Pepe,lastName:Sanchez,userName:pepe980089 ,email:pepe@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000044 ,firstName:Juan,lastName:Ortega,userName:ortigosa25 ,email:jjjyeah@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000055 ,firstName:Susana,lastName:Tomares,userName:susimares ,email:suto59@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    When I add "Content-Type" header equal to "application/json"
    And I send a "DELETE" request to "api/users/40000000-c222-c222-c222-000000000033"
    Then the response status code should be 200

  Scenario: (delete test more details) Given users in the system I want delete one of them
    Given I have a delivery company in the database with uuid:50000000-c555-c555-c555-000000000055 and name:Delivery Company 1
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000033 ,firstName:Pepe,lastName:Sanchez,userName:pepe980089 ,email:pepe@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000044 ,firstName:Juan,lastName:Ortega,userName:ortigosa25 ,email:jjjyeah@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    And I have saved user in the database with uuid:40000000-c222-c222-c222-000000000055 ,firstName:Susana,lastName:Tomares,userName:susimares ,email:suto59@4a-side.ninja and that belongs to the delivery company with uuid:50000000-c555-c555-c555-000000000055
    When I add "Content-Type" header equal to "application/json"
    And I send a "GET" request to "api/users"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    [
        {
            "deliveryCompanyUuid": "50000000-c555-c555-c555-000000000055",
            "uuid": "40000000-c222-c222-c222-000000000033",
            "firstname": "Pepe",
            "lastname": "Sanchez",
            "username": "pepe980089",
            "email": "pepe@4a-side.ninja"
        },
        {
            "deliveryCompanyUuid": "50000000-c555-c555-c555-000000000055",
            "uuid": "40000000-c222-c222-c222-000000000044",
            "firstname": "Juan",
            "lastname": "Ortega",
            "username": "ortigosa25",
            "email": "jjjyeah@4a-side.ninja"
        },
        {
            "deliveryCompanyUuid": "50000000-c555-c555-c555-000000000055",
            "uuid": "40000000-c222-c222-c222-000000000055",
            "firstname": "Susana",
            "lastname": "Tomares",
            "username": "susimares",
            "email": "suto59@4a-side.ninja"
        }
    ]
    """
    Then I am going to:DELETE FIRST USER
    When I add "Content-Type" header equal to "application/json"
    And I send a "DELETE" request to "api/users/40000000-c222-c222-c222-000000000033"
    Then the response status code should be 200
    Then I am going to:CHECK THAT THE USER IS NOT PRESENT ANY MORE
    When I add "Content-Type" header equal to "application/json"
    And I send a "GET" request to "api/users"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    [
        {
            "deliveryCompanyUuid": "50000000-c555-c555-c555-000000000055",
            "uuid": "40000000-c222-c222-c222-000000000044",
            "firstname": "Juan",
            "lastname": "Ortega",
            "username": "ortigosa25",
            "email": "jjjyeah@4a-side.ninja"
        },
        {
            "deliveryCompanyUuid": "50000000-c555-c555-c555-000000000055",
            "uuid": "40000000-c222-c222-c222-000000000055",
            "firstname": "Susana",
            "lastname": "Tomares",
            "username": "susimares",
            "email": "suto59@4a-side.ninja"
        }
    ]
    """