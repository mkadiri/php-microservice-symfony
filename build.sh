ROOT=$PWD

echo "Make sure you run this script from the root of the project"

echo "Build php docker image"
docker build -t mkadiri/php-microservice-symfony .