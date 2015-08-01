# Introduction #

Please date stamp anything you want to change in here because it makes it easier but as it's mostly just me sorting this out it's more of a reminder to myself.  So, the plan was to switch over to the new 1.7.4 Framework and change the layout of the classes a bit to the new ways I have learned.

# Goals #

  * DB Classes in different files
  * Models only contain function to do with that model
  * Rework any piece of code necessary that is currently too complex
  * Keep it Simple!

# Next #

  * ACL in controllers
    * change things like edit/delete to only be available if the user is the owner of the item in question
  * Ratings
    * DONE: Add and check counters increase
    * DONE: Delete to reduce counter
    * DONE: Test Add / Delete
    * Delete to be limited in controller (right now anyone can hack a url to delete it)
  * Comments
    * DONE: Delete
    * DONE: Test Add / Delete
    * Delete to be limited in controller (right now anyone can hack a url to delete it)
  * Method Items
    * DONE: JQuery Sortable
  * Tags
    * Add/Delete/Update Tags
  * Search
    * By Name, Ingredient, Tag
  * Redirect
    * Redirect on 404 to a 404
    * Redirect to another page if no acl
    * Redirect on login if not logged in and they asked for a specific page
  * Delete user
    * What to do with all comments/recipes/etc
    * Limit to the user (same as the ACL call with other deletes)

# Complete #

Below is a list of what I believe are complete parts of the re-work, feel free to test.

  * Make Login work properly so things start talking to the Auth object
  * Use the Auth object to submit recipes
  * Use the layout system
  * Use the Zend Forms
  * Implement an ACL, essential reading : http://codeutopia.net/blog/?s=ACL
  * Optionally show edit on recipe if user is owner
  * Only allow owner to edit recipe