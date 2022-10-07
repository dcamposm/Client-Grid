# Client-Grid

# PREREQUISITS

- Install Composer

# INSTRUCTIONS

- Copy .env.cp to .env, and change DATABASE_URL
- Create a migration to update the database  and run migration with the following commands:
	- symfony console make:migration
	- symfony console doctrine:migrations:migrate
- Use the following command to load fake data  into the database:
	- symfony console doctrine:fixtures:load
- Use the following command  to start server and open in your browser and navigate to http://localhost:8000
	- symfony server:start
