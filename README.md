# laravel-task-gui-linkage

An example to show how to connect Laravel Tasks to reusable components in the GUI (so can re-use same laravel command/task but with different timing and params for each job). Uses Command currently (might investigate to see if pointing to Controllers give more/less flexibility).

Will create another branch to see if I can extend Command class for a more OOP structure (no luck so far).

Note to get started: 
Add your mysql connection info to a Laravel .env file in root dir.
Then comment out the BatchProcess code in /app/Console/Kernel.php for now (I hint what to comment out in that schedule() code).
Run the laravel migrations to setup the tables. 
Then uncomment that Kernel.php code.
You have to do this because naturally you'd just run 'php artisan migrate' to 1st setup the tables for the app.
But Laravel when running 'php artisan migrate' seems to run also the Kernel.php file which will try to dynamically lookup and setup BatchProcesses and therefore try to hit up its corresponding table (a catch-22 since the table won't exist in 1st place and the command to create it won't run because its not there). 2nd option is to add a try/catch in that code and avoid these silently errors but not sure i want to do that.
