<h1 align="center">TRI P2P Wallet</h1>

## Instruction
First clone/download the repository

Follow the below-listed instructions to easy run

1. Install dependencies
    - composer install
    - composer dump-auto
    - npm install

2. Create .env file then copy contents from .env.example and run the following commad
    - php artisan key:generate

3. Start you LAMP/XAMPP/WAMP/MAMP server
    - Craete databse for the project

4. Set email-related .env variables to make sure mail-send working perfectly (use SMTP driver)

5. Make migratings with default seeders
    - php artisan migrate --seed 
    or 
    - php artisan migrate:fresh --seed

6. Run the the project on the server
    Run these commands on the seperate terminal
    - php artisan serve
    - npm run dev
    - [Browse the Project](http://localhost:8000/)

7. Run queue worker 
    Run this command on the seperate terminal
    - php artisan queue:work

8. Run PHPUnit test
    - php artisan test

9. You can chcek the postman api collection
    - [TRI P2P Wallet API Collection](https://asifs-team.postman.co/workspace/My-Workspace~f1158975-2e9e-4979-8c40-9bdf2695c4c2/collection/22819528-bd816e34-3a0f-4622-a7c3-854ee26177a1?action=share&creator=22819528)
    or
    - Find "TRI P2P Wallet.postman_collection.json" file inside the projects root directory

10. Check projects demonstration video
    - [TRI P2P Wallet Demo](https://screencast-o-matic.com/watch/c3XfQwVu5Tb)
