Hi folks,

It is a sample project to demostrate Microservices with Docker.

This app demonstrates the list of candidates (interviewee/jobseeker) and their feedbacks from interviewer. So it has basically 3 main components.

    1. Users microservice: This service handles add, edit and delete of users. It is created in PHP (Slimframework) and mongoDB.

    2. Interview microservice: This service keeps interview rating and feedback of above users. and It is created with PHP (Slimframework) and MySQL.

    3. GUI: This is frontend part of the application, where above microservices are consumed. So it is using PHP and other frontend technologies like HTML, CSS, Javascript, jQuery and Gulp etc.


So to make this app work properly on your localsystem. Please follow directory structure strickly.

- /var/www/html/unify-demo-app/
    - data/
        - dockermongo/
        - dockermysql/
    - microservices/
        - interview/
        - users/
    - ui/
    - docker-compose.yml
    - README.md


Commands:

    docker-compose build

    docker-compose up
