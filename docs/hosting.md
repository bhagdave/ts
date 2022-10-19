# Hosting
Hosting is done via [Heroku](https://id.heroku.com/)

There are two apps setup.  One for staging and one for production.  The config vars for running production are setup within Heropku against the app.

The API keys etc for the project are kept in the config variables inside heroku.  They are pretty much the same for both Live and Staging (Which may have to change) the only difference being the database they point to.

## Builds

The staging server is auto deployed every time something is commited to the master branch.  The master branmch needs someone to go into the Heroku app and manually do an auto deploy to get it into production.


