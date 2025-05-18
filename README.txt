The group is using XAMPP MySQL and phpMyAdmin for the database server

Before the commencement of Sprint 1. Run setup_user_tables.php in "SQL" folder
	this will create the database (cleaning_platform), table for users (users), table for cleaner profile 	(cleaner_profiles) and table for homeoner profile (homeowner_profiles)

Sprint 1 Implementation:

/project-root
│
├── db.php
├── auth.php
├── /dashboard_cleaner/
│   ├── cleaner.php
│   ├── edit_profile_cleaner.php
│   ├── profile_cleaner.php
├── /dashboard_homeowner/
│   ├── homeowner.php
│   ├── edit_profile_homeowner.php
│   ├── profile_homeowner.php
├── /dashboard_admin/
│   ├── admin.php
│   ├── suspend_user.php
│   ├── unsuspend_user.php
├── /SQL/
│   ├── setup_user_tables.php
└── README.txt


Sprint 2 Implementation:

Before the commencement of Sprint 2. Run sprint2_update.php in "SQL folder"
	this will update the cleaner_profiles table to add in "service_fee" column
	this will update the cleaner_profiles table to add in "preferred_locations" column
	this will update the cleaner_profiles table to add in "emergency_contact" column
	this will update the cleaner_profiles table to add in "unavailable_mode" column

cleaner.php has been updated to add the href "Set Service Fee"
cleaner.php has been updated to add the href "Set Preferred Locations"
cleaner.php has been updated to add the href "Set Emergency Contact"
cleaner.php has been updated to add the href "Set Availability"

/project-root
│
├── db.php
├── auth.php
├── /dashboard_cleaner/
│   ├── cleaner.php					--> Sprint 2 updated
│   ├── edit_profile_cleaner.php
│   ├── profile_cleaner.php
│   ├── set_service_fee.php			--> Sprint 2 added
│   ├── set_working_locations.php		--> Sprint 2 added
│   ├── set_emergency_contact.php		--> Sprint 2 added
│   ├── set_unavailable_mode.php		--> Sprint 2 added
├── /dashboard_homeowner/
│   ├── homeowner.php
│   ├── edit_profile_homeowner.php
│   ├── profile_homeowner.php
├── /dashboard_admin/
│   ├── admin.php
│   ├── suspend_user.php
│   ├── unsuspend_user.php
├── /SQL/
│   ├── setup_user_tables.php
│   ├── sprint2_update.php				--> Sprint 2 added
└── README.txt


Sprint 3 Implementation:

Before the commencement of Sprint 3. Run sprint3_update.php in "SQL folder"
	this will create the table for shortlisted cleaners (cleaner_shortlists)
	this will create the table for booking cleaners (cleaner_bookings)

/project-root
│
├── db.php
├── auth.php
├── /dashboard_cleaner/
│   ├── cleaner.php					
│   ├── edit_profile_cleaner.php
│   ├── profile_cleaner.php
│   ├── set_service_fee.php			
│   ├── set_working_locations.php		
│   ├── set_emergency_contact.php		
│   ├── set_unavailable_mode.php		
├── /dashboard_homeowner/
│   ├── homeowner.php					--> Sprint 3 updated
│   ├── edit_profile_homeowner.php
│   ├── profile_homeowner.php
│   ├── search_cleaners.php			--> Sprint 3 added
│   ├── view_cleaner_profile.php		--> Sprint 3 added
│   ├── shortlist_cleaner.php			--> Sprint 3 added
│   ├── view_shortlisted_cleaners.php	--> Sprint 3 added
│   ├── remove_shortlist.php			--> Sprint 3 added
│   ├── book_cleaner.php				--> Sprint 3 added
├── /dashboard_admin/
│   ├── admin.php
│   ├── suspend_user.php
│   ├── unsuspend_user.php
├── /SQL/
│   ├── setup_user_tables.php
│   ├── sprint2_update.php
│   ├── sprint3_update.php				--> Sprint 3 added				
└── README.txt

homeowner.php has been updated to add the href "Search for a Cleaner"
homeowner.php has been updated to add the href "View Shortlisted Cleaners"


Sprint 4 Implementation:

/project-root
│
├── db.php
├── auth.php
├── /dashboard_cleaner/
│   ├── cleaner.php					
│   ├── edit_profile_cleaner.php
│   ├── profile_cleaner.php			
│   ├── set_service_fee.php			
│   ├── set_working_locations.php		
│   ├── set_emergency_contact.php		
│   ├── set_unavailable_mode.php		
├── /dashboard_homeowner/
│   ├── homeowner.php					
│   ├── edit_profile_homeowner.php
│   ├── profile_homeowner.php
│   ├── search_cleaners.php			
│   ├── view_cleaner_profile.php		
│   ├── shortlist_cleaner.php			
│   ├── view_shortlisted_cleaners.php	
│   ├── remove_shortlist.php			
│   ├── book_cleaner.php				
├── /dashboard_admin/
│   ├── admin.php						--> Sprint 4 updated
│   ├── suspend_user.php
│   ├── unsuspend_user.php
│   ├── admin_monitor_cleaners.php		--> Sprint 4 added
│   ├── view_users.php					--> Sprint 4 added
├── /SQL/
│   ├── setup_user_tables.php
│   ├── sprint2_update.php
│   ├── sprint3_update.php								
└── README.txt


Sprint 5 Implementation:

Before the commencement of Sprint 5. Run sprint5_update.php in "SQL folder"
	this will update the cleaner_bookings table to add in "is_seen" column
	this will update the cleaner_bookings table to add in "homeowner_seen" column
	this will update the cleaner_bookings table to add in "rating" column
	this will update the cleaner_bookings table to add in "status_update_at" column
	this will update the cleaner_bookings.status column to add in "completed" variable
	this will create the table for messages to admin (admin_messages)

/project-root
│
├── db.php
├── auth.php
├── /dashboard_cleaner/
│   ├── cleaner.php					--> Sprint 5 updated		
│   ├── edit_profile_cleaner.php
│   ├── profile_cleaner.php			
│   ├── set_service_fee.php			
│   ├── set_working_locations.php		
│   ├── set_emergency_contact.php		
│   ├── set_unavailable_mode.php
│   ├── view_shortlists.php			--> Sprint 5 added
│   ├── manage_bookings.php			--> Sprint 5 added
│   ├── completed_jobs.php				--> Sprint 5 added
│   ├── service_match_history.php		--> Sprint 5 added
│   ├── contact_admin.php				--> Sprint 5 added	
├── /dashboard_homeowner/
│   ├── homeowner.php					--> Sprint 5 updated	
│   ├── edit_profile_homeowner.php
│   ├── profile_homeowner.php
│   ├── search_cleaners.php			
│   ├── view_cleaner_profile.php		
│   ├── shortlist_cleaner.php			
│   ├── view_shortlisted_cleaners.php	
│   ├── remove_shortlist.php			
│   ├── book_cleaner.php
│   ├── track_requests.php				--> Sprint 5 added
│   ├── service_usage_history.php		--> Sprint 5 added			
│   ├── leave_review.php				--> Sprint 5 added
│   ├── contact_admin.php				--> Sprint 5 added	
├── /dashboard_admin/
│   ├── admin.php						--> Sprint 5 updated
│   ├── suspend_user.php
│   ├── unsuspend_user.php
│   ├── admin_monitor_cleaners.php		
│   ├── view_users.php
│   ├── admin_daily_report.php			--> Sprint 5 added
│   ├── view_feedback.php				--> Sprint 5 added
├── /SQL/
│   ├── setup_user_tables.php
│   ├── sprint2_update.php
│   ├── sprint3_update.php
│   ├── sprint5_update.php				--> Sprint 5 added									
└── README.txt


