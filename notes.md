### Todo

***Right now, there is a problem with daily_limit_activate.  The problem lies in gamify-wp-settings.php, I think.  Process-class.php, line 127 needs to be $new_score.n




-Test User Stats page only accessible to logged in users.
-Need comment/post removal to subtract points.
-Hook up Custom Notification CSS.
-_e() gamify-wp-general.php and custom-actions.php
-Icon for Custom Actions/ Rewards CPT
-Rewards - check all checkboxes?
-validate (int) in forms (Rewards Meta Box, Custom Actions Meta, Plugin Settings)
-maek sure action hooks (default and custom) account for daily points limit
	=see $process->save_process_results()
	-check default actions
-find some way to notify if daily limit has been reached
	-should add progress bar to User Stats
-If two hooks are assigned to the same event, we need to have some kind of warning, or page listing duplicate hooks (maybe on stats page)
-Think about chaging the word "Level" to something like "Title" or "Rank"

-do a better job checking to see if variables exist
-esc
-__()



-Plugin to let you add custom post types like a subpage under custom settings menu (Menu Page).