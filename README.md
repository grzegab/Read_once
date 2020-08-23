# Read ONCE app
It's a demo app to show how to create simple messages that can be read once.
It can be used to send passwords or other sensitive data.

## App overview
It uses PHP 7.4 and Symfony 4.4 framework.
For development purposes it uses Docker to make app running.

## Running app
You may want to edit .env file to create more personalized containers:
copy `.env` to `.env.local` and change values in docker section.

To start an app (make sure Docker compose is installed):
`docker-compose up -d`
To stop this app:
`docker-compose down`

By default, app start running on `http://localhost:8087`.
