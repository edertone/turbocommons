# How to upgrade all the libraries and dependencies that this project uses


This project uses libraries and dependencies from a variety of sources. To make sure that all of them are up to date, follow this steps:
    
- Update all the libraries versions on:

    src/test/libs
        
- Update the library versions at the tests autoloader file:
    
    src/test/php/autoloader.php
    
- Run the project tests as explained on help and make sure everything works as expected