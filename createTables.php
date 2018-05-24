<?php
require_once "Database_Connect.php";

	$query="create table if not exists members (
			id int not null auto_increment, primary key(id),
			firstname varchar(255) not null,
			lastname varchar(255) not null,
			email varchar(255),
			slug varchar(255) not null,
			password varchar(255),
			registration_date datetime not null,
			activation_key varchar(255) not null,
			confirmed tinyint(1) not null default 0,
			suspended tinyint(1) not null default 0,
			gender char(1) not null,
			referral_code varchar(6) not null unique,
			birthdate varchar(50) not null,
			show_birthdate int not null,
			phone varchar(20),
			country_id tinyint(3),
			timezone varchar(50),
			points int(11) default 0,
			FK country_id references country(id)
			 )";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists member_contacts (
			id int not null auto_increment, primary key(id),
			memberid int not null,
			label varchar(50) not null,
			value varchar(255) not null
			FK memberid references members(id)
			)";
	// labels: address, currentcity, website, linkedin, twitter, facebook, phone
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists member_points (
			id int not null auto_increment, primary key(id),
			memberid int not null,
			value int(11) not null,
			point_type varchar(20) not null,
			current_total int(11) not null,
			date_earned datetime not null,
			FK memberid references members(id)
			)";
		mysql_query($query,$link) or die(mysql_error());


		$query="create table if not exists member_badges (
				id int not null auto_increment, primary key(id),
				memberid int not null,
				badge_type varchar(20) not null,
				date_earned datetime not null,
				FK memberid references members(id)
				)";
			mysql_query($query,$link) or die(mysql_error());
			// create list of badge type and criteria for earning.

	$query="create table if not exists country (
			id int not null auto_increment, primary key(id),
			name varchar(50) not null,
			slug varchar(20) not null
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists state (
			id int not null auto_increment, primary key(id),
			country_id
			name varchar(50) not null,
			slug varchar(20) not null
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists login_log (
			id int not null auto_increment, primary key(Id),
			memberid int not null,
			last_login timestamp not null
				DEFAULT CURRENT_TIMESTAMP
                ON UPDATE CURRENT_TIMESTAMP,
			ip varchar(50) not null,
			device_type varchar(10) not null,
			os  varchar(20) not null,
			browser  varchar(20) not null,
			FK memberid references members(id)
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists pushid (
			memberid int not null,
			browserid varchar(50),
			primary key(memberid, browserid),
			FK memberid references members(id),
				ON UPDATE CASCADE
				ON DELETE CASCADE
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists social_signup (
			memberid int not null,
			oauth_provider varchar(10) not null,
    		oauth_uid text not null,
			primary key(memberid),
			FK memberid references members(id)
				ON UPDATE CASCADE
				ON DELETE CASCADE
			)";
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists classes (
			id int not null auto_increment, primary key(id),
			classname varchar(255) not null,
			description text not null,
			logo varchar(255),
			date_created varchar(40) not null,
			createdby int not null,
			confirmed int not null,
			status tinyint(1) not null
			)";
	//status: 1 - open, 2 - locked and free, 3 - locked and paid

	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists classes_fees (
			id int not null auto_increment, primary key(id),
			classid int(11) not null,
			amount float not null,
			currency varchar(10),
			discount int not null default 0,
			FK classid references classes(id)
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists classes_admin (
			memberid int(11) not null,
			classid int(11) not null,
			primary key(memberid, classid),
			FK createdby references members(id)
			FK memberid references members(id)
				ON UPDATE CASCADE
				ON DELETE CASCADE
			FK classid references classes(id)
				ON UPDATE CASCADE
				ON DELETE CASCADE
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists featured_classes (
			id int not null auto_increment, primary key(Id),
			classid int not null,
			FK classid references classes(id)
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists suggested_classes (
			id int not null auto_increment, primary key(id),
			memberid int not null,
			classname varchar(255),
			reason text,
			description text,
			status tinyint(1)
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists class_permission (
			id int not null auto_increment, primary key(id),
			memberid int not null,
			classid int not null,
			date_sent varchar(60),
			permitted int
			)";
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists members_class_relationship (
			memberid int not null,
			classid int(11) not null,
			date_joined datetime not null,
			primary key(memberid, classid),
			FK memberid references members(id)
				ON UPDATE CASCADE
				ON DELETE CASCADE,
			FK classid references classes(id)
			)";
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists classes_default_questions (
			id int(11) not null, primary key(id),
			question text not null,
			data_type varchar(255),
			form_element_type varchar(255),
			value text
			)";
			/* value we can set constants like state, country, courses, skills:
			when we see we populate on frontend with equivalent data
			for other values that are not constants we use hypen to establish
			value-label relationship and comma to separate options
			eg book-amazon,song-pop
			*/
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists classes_default_responses (
			questionid int(11) not null,
			memberid int(11) not null,
			classid int(11) not null,
			value text not null,
			primary key(questionid,memberid,live_classid),
			FK classid references classes(id),
			FK memberid references members(id),
			FK questionid references classes_default_questions(id),
			)";

	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists class_posts (
			id int not null auto_increment, primary key(id),
			title varchar(255) not null,
			memberid int(11) not null,
			classid int(11) not null,
			content bigtext not null,
			date_posted varchar(255) not null,
			FK classid references classes(id),
			FK memberid references members(id)
			)";
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists classes_posts_comments (
			id int not null auto_increment, primary key(Id),
			memberid int(11) not null,
			postid int(11) not null,
			content text not null,
			date_posted varchar(255) not null,
			FK postid references classes_posts(id),
			FK memberid references members(id)
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists classes_files (
			id int not null auto_increment, primary key(id),
			postid int(11) not null,
			file_name varchar(255),
			file_link varchar(255),
			mime_type varchar(50),
			file_size varchar(20),
			FK postid references classes_posts(id),
			)";
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists classses_posts_ratings (
			postid int(11) not null,
			memberid int(11) not null,
			rating_date datetime not null,
			rating_type varchar(20) not null,
			primary key(memberid,postid),
			FK postid references classes_posts(id),
			FK memberid references members(id)
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists classes_comments_ratings (
			commentid int(11) not null,
			memberid int(11) not null,
			rating_date datetime not null,
			primary key(memberid,commentid),
			rating_type varchar(20) not null,
			FK commentid references classes_posts_comments(id),
			FK memberid references members(id)
			)";
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists classses_posts_reports (
			postid int(11) not null,
			memberid int(11) not null,
			report_date datetime not null,
			comment text not null,
			primary key(memberid,postid),
			FK postid references classes_posts(id),
			FK memberid references members(id)
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists classes_comments_reports (
			commentid int(11) not null,
			memberid int(11) not null,
			report_date datetime not null,
			comment text not null,
			FK commentid references classes_posts_comments(id),
			FK memberid references members(id)
			)";
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists pending_friends (
			id int not null auto_increment, primary key(id),
			senderid int not null,
			receiverid int not null,
			date_sent datetime not null,
			status tinyint (1) default 0
			)";
	//pending:0, accepted: 1, rejected: 2
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists skills (
			id int not null auto_increment, primary key(Id),
			name varchar(255) not null,
			slug varchar (255) not null,
			status tinyint(1) default 0
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists members_skills_relationship (
		memberid int not null,
		skillid int(11) not null,
		primary key(memberid, skillid),
		FK memberid references members(id)
			ON UPDATE CASCADE
			ON DELETE CASCADE,
		FK skillid references skills(id)
			ON UPDATE CASCADE
			ON DELETE CASCADE,
		)";
	mysql_query($query,$link) or die(mysql_error());



	*** start here ***

		$query="create table if not exists live_classes (
			id int not null auto_increment, primary key(id),
			topic varchar(255) not null,
			classid int not null,
			description text not null,
			start_time datetime not null,
			end_time datetime not null,
			actual_start_time datetime not null,
			actual_end_time datetime not null,
			prerequisites text,
			banner varchar(255),
			date_created varchar(40) not null,
			registration_deadline datetime not null,
			createdby int not null,
			status tinyint(1) not null,
			FK classid references classes(id)

			)";

	//status: 0 = free, 1=paid only, 2=paid and refer free, 3=refer only
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists live_classes_fees (
			id int not null auto_increment, primary key(id),
			live_classid int(11) not null,
			amount float not null,
			discount int not null default 0,
			FK live_classid references live_classes(id)
			)";
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists live_classes_resources (
			id int not null auto_increment, primary key(id),
			live_classid int(11) not null,
			file_name varchar(255),
			file_link varchar(255),
			mime_type varchar(50),
			file_size varchar(20),
			FK live_classid references live_classes(id)
			)";
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists live_classes_members (
			live_classid int(11) not null,
			memberid int(11) not null,
			primary key(memberid,live_classid),
			status tinyint(1) default 0,
			date_joined datetime not null,
			FK live_classid references live_classes(id),
			FK memberid references members(id)
			)";


	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists live_classes_attendees (
			live_classid int(11) not null,
			memberid int(11) not null,
			primary key(memberid,live_classid),
			time_joined datetime not null,
			last_seen datetime not null,
			flag varchar(60) not null,
			FK live_classid references live_classes(id),
			FK memberid references members(id)
			)";
	mysql_query($query,$link) or die(mysql_error());
	/* Find out what flag does  - Should have means to mute a member */

	$query="create table if not exists live_classes_default_questions (
			id int(11) not null, primary key(id),
			question text not null,
			data_type varchar(255),
			form_element_type varchar(255),
			value text
			)";
			/* value we can set constants like state, country, courses, skills:
			when we see we populate on frontend with equivalent data
			for other values that are not constants we use hypen to establish
			value-label relationship and comma to separate options
			eg book-amazon,song-
			*/
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists live_classes_default_responses (
			questionid int(11) not null,
			memberid int(11) not null,
			live_classid int(11) not null,
			value text not null,
			primary key(questionid,memberid,live_classid),
			FK live_classid references live_classes(id),
			FK memberid references members(id),
			FK questionid references live_classes_default_questions(id),
			)";

	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists live_classes_questions (
			id int(11) not null, primary key(id),
			live_classid int(11) not null,
			question text not null,
			data_type varchar(255),
			form_element_type varchar(255),
			value text,
			optional tinyint(1) default 1,
			FK live_classid references live_classes(id)
			)";
			/* value we can set constants like state, country, courses, skills:
			when we see we populate on frontend with equivalent data
			for other values that are not constants we use hypen to establish
			value-label relationship and comma to separate options
			eg book-amazon,song-
			*/
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists live_classes_responses (
			questionid int(11) not null,
			memberid int(11) not null,
			live_classid int(11) not null,
			value text not null,
			primary key(questionid,memberid,live_classid),
			FK live_classid references live_classes(id),
			FK memberid references members(id),
			FK questionid references live_classes_default_questions(id),
			)";

	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists live_classes_audio (
			id int(11) not null, primary key(id),
			live_classid int(11) not null,
			audio_file varchar(255) not null,
			FK live_classid references live_classes(id)
			)";

	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists chatroom (
			id int(11) not null auto_increment, primary key(id),
			memberid int(11) not null,
			live_classid int(11) not null,
			chat_date datetime not null,
			body text not null)";
	mysql_query($query,$link) or die(mysql_error());

	/*
	Equivalent to live_classes_attendees

	$query="create table if not exists onlineroom (
			id int not null auto_increment, primary key(id),
			memberid varchar(25) not null,
			live_classid varchar(25) not null,
			flag varchar(60) not null,
			entrytime varchar(60) not null)";
	mysql_query($query,$link) or die(mysql_error());
*/
$query="create table if not exists live_classes_referrals (
		id int(11) not null auto_increment,
		memberid int(11) not null,
		member_referred_id(11) not null,
		live_classid int(11) not null,
		primary key(memberid, member_referred_id, live_classid),
		referral_date datetime not null
		)";
mysql_query($query,$link) or die(mysql_error());



	$query="create table if not exists live_classes_test (
			id int(11) not null auto_increment, primary key(id),
			question_text text not null,
			live_classid int(11) not null,
			status tinyint(1) not null default 0)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists live_classes_sub_test (
			id int(11) not null auto_increment, primary key(id),
			sub_questions text not null,
			options text not null,
			right_option text not null,
			class_test_id int(11))";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists test_timing(
			id int not null auto_increment, primary key(id),
			live_classid int(11) not null,
			duration int(11) not null,
			pass_mark int(11) not null
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists publish_test (
			id int not null auto_increment, primary key(id),
			memberid int(11) not null,
			live_classid int(11) not null,
			score float not null,
			date_taken datetime not null
			)";
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists live_classes_test_answers (
			questionid int(11) not null,
			memberid int(11) not null,
			live_classid int(11) not null,
			value text not null,
			primary key(questionid,memberid),
			FK memberid references members(id),
			FK questionid references live_classes_sub_test(id),
			)";

	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists mods (
			id int not null auto_increment, primary key(id, memberid),
			memberid int,
			permit text)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists mods_pages (
			id int not null auto_increment, primary key(id, page),
			page varchar(255),
			pagetitle varchar(255))";
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists logged (
			id int not null auto_increment, primary key(id),
			memberid int not null,
			timeupdate varchar(255)
			)";
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists news_categories (
			id int not null auto_increment, primary key(id),
			categoryname varchar(255))";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists news_details (
			id int not null auto_increment, primary key(id),
			categoryid int not null,
			title text not null,
			body text not null,
			pic varchar(255),
			sender varchar(255),
			date varchar(60) not null)";
	mysql_query($query,$link) or die(mysql_error());



	$query="create table if not exists newspics (
			id int not null auto_increment, primary key(id),
			name varchar(255) not null,
			tag varchar(255) not null
			)";
	mysql_query($query,$link) or die(mysql_error());


	$query="create table if not exists news_comments (
			id int not null auto_increment, primary key(id),
			memberid int not null,
			postid int not null,
			comment text not null,
			commentdate varchar(50))";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists blog (
			id int not null auto_increment, primary key(id),
			title varchar(255) not null,
			description text not null,
			blog_photo varchar(255),
			blog_category varchar(255),
			tags varchar(255),
			upload_date  varchar(255),
			featured int not null)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists blog_comments (
			id int not null auto_increment, primary key(id),
			memberid int not null,
			postid int not null,
			comment text not null,
			commentdate varchar(50))";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists blogpics (
			id int not null auto_increment, primary key(id),
			name varchar(255) not null,
			tag varchar(255) not null
			)";
	mysql_query($query,$link) or die(mysql_error());



	$query="create table if not exists adminaccess (
			id int not null auto_increment, primary key(id),
			username varchar(55) not null,
			password varchar(55) not null
			)";
	mysql_query($query,$link) or die(mysql_error());



	$query="create table if not exists email_settings (
			id int not null auto_increment, primary key(id, memberid),
			memberid int not null,
			addfriend int not null,
			friendapproved int not null,
			internalbox int not null,
			replytopost int not null,
			studyclassapproved int not null,
			testapproved int not null,
			newsposts int not null,
			newscomments int not null,
			joinclassrequest int not null,
			joinclassapproved int not null,
			takequiz int not null,
			postonclasses int not null,
			postonclassescreated int not null,
			newnews int not null,
			newgist int not null,
			newblog int not null,
			replystatus int not null default 1
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists inbox (
			id int not null auto_increment, primary key(id),
			senderid int not null,
			receiverid int not null,
			message text,
			date_sent varchar(255),
			parentid text,
			rec_read int,
			rec_delete int,
			sender_delete int
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists tasks (
			id int not null auto_increment, primary key(id),
			memberid int not null,
			email varchar(255) not null,
			confirmed int not null,
			subject text,
			message text
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists notices (
			id int not null auto_increment, primary key(id),
			memberid int not null,
			message text not null,
			pic text not null,
			status int not null,
			date_sent varchar(255)
			)";
	mysql_query($query,$link) or die(mysql_error());


	/*$query="insert into adminaccess values ('', 'skoola', 'ola')";
		mysql_query($query, $link) or die (mysql_error());

	*/

?>
