# Direct Messaging
So this has been a big piece of work that has changed lots of the site.  In fact I think I really have dabbled everywhere.  Anyway this is a document to go through how you get Direct Messages running locally.

Direct Messages uses laravel notifictions with Pusher to get notifications to each user.  The notifiations only work if you have a Larvel queue worker running.  So for local development you will need to get one running.  It is fairly easy to do so, 'php artisan queue:work`.  BUT, before you do that you will have to make sure you have run all of the migrations.  The notifications and queue worker put the jobs in a database and work their way through it.

The notifications are then pushed through Pusher which i spicked up as events in the VUE components on the front end.  These are used to inject data into the page etc.


