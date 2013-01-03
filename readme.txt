=== GetShopped Support Forums ===
Contributors: mychelle, mufusa, getshopped
Tags: bbPress 2.0, Support forum, resolved topics, user rankings
Requires at least: 3.2
Tested up to: 3.2.1
Stable tag: 3.0

The GetShopped support forums plugin is used with bbPress to transform your forums into a support forum.

== Description ==

Pick and choose which forums you would like to turn into a support forum. Turning your forums into a support forum will display topic statuses that admin's, moderators and the topic creator can change. You can also implement a user ranking system through the forum settings.
For extra functionality check out the GetShopped_support_forum_widgets.

This Plugin requires the new bbPress 2.0 plugin and will not work with their older versions you can find the bbPress plugin here: http://wordpress.org/extend/plugins/bbpress/

== Installation ==

1. Upload `getshopped_support_forum` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You Must be using at least BBPress 2.0 RC 3 As it contains important template tags

= Creating a Support forum =

1. The settings for this plugin are included in with the bbPress Forum settings (Settings > Forums) you will need to head over there and configure them first.
2. To create a support forum simply check the "Support forum" checkbox in the forum attributes settings.

= Configuring the User Ranking System =

1. Enter in your rank title - eg newbie This is the name that will get displayed below the users name in the forums.
2. The next value is the minimum number of required posts (these include topics and replies) that a user must have to earn this title.
3. The last value is the maximum number of posts the user will have before the title is removed (and they will go up a rank assumably)
4. The Last option is the show the users post count below their gravatar - this will display how many replies / topics the user has created.

5.Note: For a consistent flow from rank to rank then you need to make sure that you set up your post counts right so that when one title ends (at say 20 posts) the next one will begin at 21 posts, also start your count at 1 not 0.
eg:
User ranking level 1
Rank Title
NewBie

is granted when a user has
at least 1 posts but
not more than 20 posts

User ranking level 2
Rank Title
Familiar
is granted when a user has
at least 21 posts but
not more than 50 posts
= Configuring the topic status settings =

1. Default status - This is the status that all support forum topics will start out as the default is not resolved.
2. The Display status options relate to which status options will be shown in the list for the admin, moderators and topic creator to change the topic to. by default all the options are selected (not resolved, resolved, not a support question) If you don't want one of these options then uncheck the box.
3. Admin - checking this will allow the admin to update any topic status - default is checked
4. Forum Moderator -  checking this will allow the Forum Moderators to update any topic status - default is checked
5. Topic Creator -  checking this will allow the topic creator to update any topic status - default is checked
= Configuring the Support Forum settings =

1. Urgent Topic Status -  Will allow forum admin and moderators to mark topics as urgent an [urgent]t title will then display next to the topic title
2. Move Topics - Selecting this will enable admin and forum moderators to move topics to other forums
3. Assign Topics - Selecting this will enable admin and forum moderators to assign topics to other forum admin or moderators
4. Claim topics - Allow the forum moderators and admin to claim a topic, this will mark the topic title with [claimed] but will only show to forum moderators and admin users
5. Display username - By selecting this option if a topic is claimed the claimed persons username will be displayed next to the topic title instead of the words [claimed], leaving this unchecked will default to [claimed]
== Screenshots ==

1. Creating a Support Forum
2. The user rank Settings
3. Topic status settings
4. Front end forum with topic status drop down list
5. Front end forum with topic statuses
6. User rank display eg

== Changelog ==
=3.0=
* warnings and bugs fixed
* added in the ability to have a trusted user tag for forum admin and mods

= 2.0 =
* added in new widgets (topic claim, urgent topics, recently resolved)
* added in the ability for forum moderators and admin to mark topics as urgent
* added in the ability for forum moderators and admin to claim topics
* added in the ability for forum moderators and admin to assign topics to other forum moderators and admin
* added in a topic move
* fixed some bugs and warnings

= 1.0 Beta1 =
* the Plugin is Launched for testing

== Future plans and Ideas ==

1. Option for Admin to add their own statuses to the drop down list
2. Option for the Admin to select what colours will be displayed for each status - the default at the moment is Green for resolved topics only.
3. Allow the admin to apply default ranks to certain capabilities - eg admin are always "forum maters" essentially overriding the user ranking system.
4. Allow admin to select other text that can be displayed with the users information - eg Admin users and Forum Moderators display a "trusted" message below their ranking.


