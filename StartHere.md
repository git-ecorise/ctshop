#If you're new to this project you can start here.

# Introduction #

This page will get you started in downloading all necessary files, setting up your environment, launch the application, standards we try to keep in the project. Links to all relevant wiki pages.

# A bit about the project #

CT-Shop is built using CodeIgniter php MVC framework, so any experience you might have with CodeIgniter or MVC will come in handy. The project also uses a few open source libraries like php.activerecord. These will be discussed in other topics.

# Initial project setup #

  1. First things first you have to check out the code from svn (If you don't know how to do that, you should probably study a bit more about software development, but nevertheless you're welcome to join the project).
  1. After checking out the code one of the most important things you should do is to set up the database for the application. If you downloaded the source you might have a configuration file that's not pointing to the right database (this will be changed in the future). This must be deleted before any other action. The file is located under: `...project_root/basecamp/application/config/site_config.php`. After that when you access the app it should redirect you to the database setup utility.
  1. If you get an error at this stage you might not have the rewrite module loaded for apache, or another common error might be that you have to enable php short-tags. TODO: link to a tutorial explaining what each of those mean and how to fix the problem.
  1. You should now be able to get to the admin page and put some content.

# Environment & tools #

I prefer using IntelliJ IDEA as my IDE, but Eclipse, Notepad++ or any editor will work just fine, as long as the code formatting options are the same.

  * Prefer 4 spaces over tabs. (keeps every editor happy)
  * Opening braces on the same line as the method declaration.
  * Standard line indenting style.

The project is still in it's early stage, but I propose to have a set of tools like sql diagram editor, build/deploy tools configured and ready to run. maybe automate upgrades from one version to another, automate database changes between revisions.

# Project #

The project management part of CT-Shop will be handled via:
https://www.gravitydev.com/project/29890

This is a free tool, for agile development, I hope everyone likes it.
In the long term I will try to get an open source JIRA license with GreenHopper and possibly Crucible for code-reviews. Just have to figure out where to host it as it requires quite a bit of RAM and CPU to run.