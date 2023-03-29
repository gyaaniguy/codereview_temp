# Test for Upwork

### This is a composer based package that follows psr-12 standards, and uses PHPunit for testing and code demonstration


--  
Code Style:
1. psr-12
2. composer
3. phpunit




## Code Structure
Model Directory: 
This is where the main code is.

```
Users 
    -> AbstractUser
        -> Student
        -> AbstractGuardian
            -> Teacher
            -> Parent
Message

Helpers:
    Enums: DIY enum to enforce message types
    Validation: Validation class to validate user input
Traits:
    SalutaionTrait: Common code for GuardianAbstract.
                    Rationale: 1) avoid code duplication. To demostrate I can use these.
```

# Usage

1. composer install
2. phpunit tests/*

The tests are stored in the tests/ directory.
MessageTest.php:
UserTest.php:
These should be self explanatory. Basically I have tried to test for each condition as mentioned in the doc


