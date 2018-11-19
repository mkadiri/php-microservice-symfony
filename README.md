## Installation

To get the application up and running, do the following from the root of the `php-microservice-symfony` directory:
- run `bash build.sh` from the root folder, this will create the docker images you need
- run `docker rm -f $(docker ps -qa)  && docker-compose up -d && docker logs -f php-microservice-symfony`
    - `docker rm -f $(docker ps -qa)` will remove all running containers on you machine (if you experience port issues)
    - `docker-compose up -d` will run all the docker containers without log output
    - `docker logs -f php-microservice-symfony` will print out the container logs
    
## Usage
To see the service in action you'll need to make a call to the apis, you can do so with an application such as `POSTMAN`

- Below are the 3 API routes you have available along with data you'll need to submit
``` 
    Method - POST 
    URL - http://localhost/ad
    body -
    {
    	"authToken":"TkpJe8qr9hjbqPwCHi0n", 
    	"title": "ad 1", 
    	"description": "this is my ad", 
    	"price": 11.99
    }
    
    
    Method - PUT
    URL - http://localhost/ad/{id} (e.g. http://localhost/ad/1 can be used after POST has been fired)
    body -
    {
    	"authToken": "TkpJe8qr9hjbqPwCHi0n", 
    	"title": "ad 1 edited", 
    	"description": "edited desc", 
    	"price": 12.50
    	
    }
    
    
    Method - GET
    URL - http://localhost/ad/list  
 ```
 
 
 ## Folder structure
 - `app\src` - This is where you'll find the actual source code of the application, here you'll see entities, services and controllers
 - `app\tests` - PHP unit tests, this can be run with the command `vendor/bin/phpunit` on the container (`docker exec -ti php-microservice-symfony sh` first)
 
 
 ## Future improvements
 - Need to add user friendly pages for unavailable api endpoints
 - Add more unit tests
 - Add api tests