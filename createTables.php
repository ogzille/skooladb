<?php
require_once "Database_Connect.php";

	$query="CREATE TABLE IF NOT EXISTS members (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			firstname VARCHAR (255) NOT NULL,
			lastname VARCHAR (255) NOT NULL,
			email VARCHAR (255),
			slug VARCHAR (255) NOT NULL,
			password VARCHAR (255),
			registration_date DATETIME NOT NULL,
			activation_key VARCHAR (255) NOT NULL,
			confirmed TINYINT (1) NOT NULL DEFAULT 0,
			suspended TINYINT (1) NOT NULL DEFAULT 0,
			gender CHAR(1) NOT NULL,
			referral_code VARCHAR (6) NOT NULL unique,
			birthdate VARCHAR (50) NOT NULL,
			show_birthdate TINYINT (1) NOT NULL,
			phone VARCHAR (20),
			country_id TINYINT (3),
			timezone VARCHAR (50),
			points INT (11) DEFAULT 0,
			FOREIGN KEY (country_id) REFERENCES country(id)
			 )";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS member_contacts (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid INT (11) NOT NULL,
			label VARCHAR (50) NOT NULL,
			value VARCHAR (255) NOT NULL
			FOREIGN KEY (memberid) REFERENCES members(id)
			)";
	// labels: address, currentcity, website, linkedin, twitter, facebook, phone
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS member_points (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid INT (11) NOT NULL,
			value INT (11) NOT NULL,
			point_type VARCHAR (20) NOT NULL,
			current_total INT (11) NOT NULL,
			date_earned DATETIME NOT NULL,
			FOREIGN KEY (memberid) REFERENCES members(id)
			)";
		mysqli_query($link,$query) or die(mysqli_error());


		$query="CREATE TABLE IF NOT EXISTS member_badges (
				id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
				memberid INT (11) NOT NULL,
				badge_type VARCHAR (20) NOT NULL,
				date_earned DATETIME NOT NULL,
				FOREIGN KEY (memberid) REFERENCES members(id)
				)";
			mysqli_query($link,$query) or die(mysqli_error());
			// create list of badge type and criteria for earning.

	$query="CREATE TABLE IF NOT EXISTS country (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			name VARCHAR (50) NOT NULL,
			slug VARCHAR (20) NOT NULL
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS state (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			country_id
			name VARCHAR (50) NOT NULL,
			slug VARCHAR (20) NOT NULL
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS login_log (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid INT (11) NOT NULL,
			last_login TIMESTAMP NOT NULL
				DEFAULT CURRENT_TIMESTAMP
                ON UPDATE CURRENT_TIMESTAMP,
			ip VARCHAR (50) NOT NULL,
			device_type VARCHAR (10) NOT NULL,
			os  VARCHAR (20) NOT NULL,
			browser  VARCHAR (20) NOT NULL,
			FOREIGN KEY (memberid) REFERENCES members(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS pushid (
			memberid INT (11) NOT NULL,
			browserid VARCHAR (50),
			PRIMARY KEY (memberid, browserid),
			FOREIGN KEY (memberid) REFERENCES members(id),
				ON UPDATE CASCADE
				ON DELETE CASCADE
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS social_signup (
			memberid INT (11) NOT NULL,
			oauth_provider VARCHAR (10) NOT NULL,
    		oauth_uid TEXT NOT NULL,
			PRIMARY KEY (memberid),
			FOREIGN KEY (memberid) REFERENCES members(id)
				ON UPDATE CASCADE
				ON DELETE CASCADE
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS classes (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			classname VARCHAR (255) NOT NULL,
			description TEXT NOT NULL,
			logo VARCHAR (255),
			date_created VARCHAR (40) NOT NULL,
			createdby INT NOT NULL,
			confirmed INT NOT NULL,
			status TINYINT (1) NOT NULL
			)";
	//status: 1 - open, 2 - locked and free, 3 - locked and paid

	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS classes_fees (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			classid INT (11) NOT NULL,
			amount FLOAT NOT NULL,
			currency VARCHAR (10),
			discount INT NOT NULL DEFAULT 0,
			FOREIGN KEY (classid) REFERENCES classes(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS classes_admin (
			memberid INT (11) NOT NULL,
			classid INT (11) NOT NULL,
			PRIMARY KEY (memberid, classid),
			FOREIGN KEY (createdby) REFERENCES members(id)
			FOREIGN KEY (memberid) REFERENCES members(id)
				ON UPDATE CASCADE
				ON DELETE CASCADE
			FOREIGN KEY (classid) REFERENCES classes(id)
				ON UPDATE CASCADE
				ON DELETE CASCADE
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS featured_classes (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			classid INT (11) NOT NULL,
			FOREIGN KEY (classid) REFERENCES classes(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS suggested_classes (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid INT (11) NOT NULL,
			classname VARCHAR (255),
			reason TEXT,
			description TEXT,
			status TINYINT (1)
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS class_permission (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid INT (11) NOT NULL,
			classid INT (11) NOT NULL,
			date_sent VARCHAR (60),
			permitted TINYINT (1)
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS members_class_relationship (
			memberid INT (11) NOT NULL,
			classid INT (11) NOT NULL,
			date_joined DATETIME NOT NULL,
			PRIMARY KEY (memberid, classid),
			FOREIGN KEY (memberid) REFERENCES members(id)
				ON UPDATE CASCADE
				ON DELETE CASCADE,
			FOREIGN KEY (classid) REFERENCES classes(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS classes_default_questions (
			id INT (11) NOT NULL, PRIMARY KEY (id),
			question text NOT NULL,
			data_type VARCHAR (255),
			form_element_type VARCHAR (255),
			value TEXT
			)";
			/* value we can set constants like state, country, courses, skills:
			when we see we populate on frontend with equivalent data
			for other values that are not constants we use hypen to establish
			value-label relationship and comma to separate options
			eg book-amazon,song-pop
			*/
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS classes_default_responses (
			questionid INT (11) NOT NULL,
			memberid INT (11) NOT NULL,
			classid INT (11) NOT NULL,
			value TEXT NOT NULL,
			PRIMARY KEY (questionid,memberid,live_classid),
			FOREIGN KEY (classid) REFERENCES classes(id),
			FOREIGN KEY (memberid) REFERENCES members(id),
			FOREIGN KEY (questionid) REFERENCES classes_default_questions(id),
			)";

	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS class_posts (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			title VARCHAR (255) NOT NULL,
			memberid INT (11) NOT NULL,
			classid INT (11) NOT NULL,
			parentid INT (11) NOT NULL DEFAULT 0,
			content MEDIUMTEXT NOT NULL,
			date_posted VARCHAR (255) NOT NULL,
			last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
				ON UPDATE CURRENT_TIMESTAMP
			FOREIGN KEY (classid) REFERENCES classes(id),
			FOREIGN KEY (memberid) REFERENCES members(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS classes_posts_comments (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid INT (11) NOT NULL,
			postid INT (11) NOT NULL,
			content TEXT NOT NULL,
			date_posted VARCHAR (255) NOT NULL,
			FOREIGN KEY (postid) REFERENCES classes_posts(id),
			FOREIGN KEY (memberid) REFERENCES members(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS classes_files (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			postid INT (11) NOT NULL,
			file_name VARCHAR (255),
			file_link VARCHAR (255),
			mime_type VARCHAR (50),
			file_size VARCHAR (20),
			FOREIGN KEY (postid) REFERENCES classes_posts(id),
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS classses_posts_ratings (
			postid INT (11) NOT NULL,
			memberid INT (11) NOT NULL,
			rating_date DATETIME NOT NULL,
			rating_type VARCHAR (20) NOT NULL,
			PRIMARY KEY (memberid,postid),
			FOREIGN KEY (postid) REFERENCES classes_posts(id),
			FOREIGN KEY (memberid) REFERENCES members(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS classes_comments_ratings (
			commentid INT (11) NOT NULL,
			memberid INT (11) NOT NULL,
			rating_date DATETIME NOT NULL,
			PRIMARY KEY (memberid,commentid),
			rating_type VARCHAR (20) NOT NULL,
			FOREIGN KEY (commentid) REFERENCES classes_posts_comments(id),
			FOREIGN KEY (memberid) REFERENCES members(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS classses_posts_reports (
			postid INT (11) NOT NULL,
			memberid INT (11) NOT NULL,
			report_date DATETIME NOT NULL,
			comment TEXT NOT NULL,
			PRIMARY KEY (memberid,postid),
			FOREIGN KEY (postid) REFERENCES classes_posts(id),
			FOREIGN KEY (memberid) REFERENCES members(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS classes_comments_reports (
			commentid INT (11) NOT NULL,
			memberid INT (11) NOT NULL,
			report_date DATETIME NOT NULL,
			comment TEXT NOT NULL,
			FOREIGN KEY (commentid) REFERENCES classes_posts_comments(id),
			FOREIGN KEY (memberid) REFERENCES members(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS pending_friends (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			senderid INT (11) NOT NULL,
			receiverid INT (11) NOT NULL,
			date_sent DATETIME NOT NULL,
			status TINYINT (1) DEFAULT 0
			)";
	//pending:0, accepted: 1, rejected: 2
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS skills (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			name VARCHAR (255) NOT NULL,
			slug VARCHAR  (255) NOT NULL,
			status TINYINT (1) DEFAULT 0
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS members_skills_relationship (
		memberid INT (11) NOT NULL,
		skillid INT (11) NOT NULL,
		PRIMARY KEY (memberid, skillid),
		FOREIGN KEY (memberid) REFERENCES members(id)
			ON UPDATE CASCADE
			ON DELETE CASCADE,
		FOREIGN KEY (skillid) REFERENCES skills(id)
			ON UPDATE CASCADE
			ON DELETE CASCADE,
		)";
	mysqli_query($link,$query) or die(mysqli_error());

		$query="CREATE TABLE IF NOT EXISTS live_classes (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			topic VARCHAR (255) NOT NULL,
			classid INT (11) NOT NULL,
			description TEXT NOT NULL,
			start_time DATETIME NOT NULL,
			end_time DATETIME NOT NULL,
			actual_start_time DATETIME NOT NULL,
			actual_end_time DATETIME NOT NULL,
			prerequisites TEXT,
			banner VARCHAR (255),
			date_created VARCHAR (40) NOT NULL,
			registration_deadline DATETIME NOT NULL,
			createdby INT (11) NOT NULL,
			status TINYINT (1) NOT NULL,
			FOREIGN KEY (classid) REFERENCES classes(id)

			)";

	//status: 0 = free, 1=paid only, 2=paid and refer free, 3=refer only
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS live_classes_fees (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			live_classid INT (11) NOT NULL,
			amount FLOAT NOT NULL,
			discount TINYINT (3) NOT NULL DEFAULT 0,
			FOREIGN KEY (live_classid) REFERENCES live_classes(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS live_classes_resources (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			live_classid INT (11) NOT NULL,
			file_name VARCHAR (255),
			file_link VARCHAR (255),
			mime_type VARCHAR (50),
			file_size VARCHAR (20),
			FOREIGN KEY (live_classid) REFERENCES live_classes(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS live_classes_members (
			live_classid INT (11) NOT NULL,
			memberid INT (11) NOT NULL,
			PRIMARY KEY (memberid,live_classid),
			status TINYINT (1) DEFAULT 0,
			date_joined DATETIME NOT NULL,
			FOREIGN KEY (live_classid) REFERENCES live_classes(id),
			FOREIGN KEY (memberid) REFERENCES members(id)
			)";


	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS live_classes_attendees (
			live_classid INT (11) NOT NULL,
			memberid INT (11) NOT NULL,
			PRIMARY KEY (memberid,live_classid),
			time_joined DATETIME NOT NULL,
			last_seen DATETIME NOT NULL,
			flag VARCHAR (60) NOT NULL,
			FOREIGN KEY (live_classid) REFERENCES live_classes(id),
			FOREIGN KEY (memberid) REFERENCES members(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());
	/* Find out what flag does  - Should have means to mute a member */

	$query="CREATE TABLE IF NOT EXISTS live_classes_default_questions (
			id INT (11) NOT NULL, PRIMARY KEY (id),
			question TEXT NOT NULL,
			data_type VARCHAR (255),
			form_element_type VARCHAR (255),
			value TEXT
			)";
			/* value we can set constants like state, country, courses, skills:
			when we see we populate on frontend with equivalent data
			for other values that are not constants we use hypen to establish
			value-label relationship and comma to separate options
			eg book-amazon,song-
			*/
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS live_classes_default_responses (
			questionid INT (11) NOT NULL,
			memberid INT (11) NOT NULL,
			live_classid INT (11) NOT NULL,
			value text NOT NULL,
			PRIMARY KEY (questionid,memberid,live_classid),
			FOREIGN KEY (live_classid) REFERENCES live_classes(id),
			FOREIGN KEY (memberid) REFERENCES members(id),
			FOREIGN KEY (questionid) REFERENCES live_classes_default_questions(id),
			)";

	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS live_classes_questions (
			id INT (11) NOT NULL, PRIMARY KEY (id),
			live_classid INT (11) NOT NULL,
			question text NOT NULL,
			data_type VARCHAR (255),
			form_element_type VARCHAR (255),
			value text,
			optional TINYINT (1) DEFAULT 1,
			FOREIGN KEY (live_classid) REFERENCES live_classes(id)
			)";
			/* value we can set constants like state, country, courses, skills:
			when we see we populate on frontend with equivalent data
			for other values that are not constants we use hypen to establish
			value-label relationship and comma to separate options
			eg book-amazon,song-
			*/
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS live_classes_responses (
			questionid INT (11) NOT NULL,
			memberid INT (11) NOT NULL,
			live_classid INT (11) NOT NULL,
			value text NOT NULL,
			PRIMARY KEY (questionid,memberid,live_classid),
			FOREIGN KEY (live_classid) REFERENCES live_classes(id),
			FOREIGN KEY (memberid) REFERENCES members(id),
			FOREIGN KEY (questionid) REFERENCES live_classes_questions(id),
			)";

	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS live_classes_audio (
			id INT (11) NOT NULL, PRIMARY KEY (id),
			live_classid INT (11) NOT NULL,
			audio_file VARCHAR (255) NOT NULL,
			FOREIGN KEY (live_classid) REFERENCES live_classes(id)
			)";

	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS live_classes_series (
			id INT (11) NOT NULL, PRIMARY KEY (id),
			title VARCHAR (255) NOT NULL,
			date_created DATETIME NOT NULL
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS live_classes_series_rel (
			seriesid INT (11) NOT NULL,
			live_classid INT (11) NOT NULL,
			tree TINYINT (3) NOT NULL,
			PRIMARY KEY (seriesid,live_classid),
			FOREIGN KEY (seriesid) REFERENCES live_classes_series(id)
			FOREIGN KEY (live_classid) REFERENCES live_classes(id)
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS chatroom (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid INT (11) NOT NULL,
			live_classid INT (11) NOT NULL,
			chat_date DATETIME NOT NULL,
			body TEXT NOT NULL)";
	mysqli_query($link,$query) or die(mysqli_error());

	/*
	Equivalent to live_classes_attendees

	$query="CREATE TABLE IF NOT EXISTS onlineroom (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid VARCHAR (25) NOT NULL,
			live_classid VARCHAR (25) NOT NULL,
			flag VARCHAR (60) NOT NULL,
			entrytime VARCHAR (60) NOT NULL)";
	mysqli_query($link,$query) or die(mysqli_error());
*/
$query="CREATE TABLE IF NOT EXISTS live_classes_referrals (
		id INT (11) NOT NULL AUTO_INCREMENT,
		memberid INT (11) NOT NULL,
		member_referred_id(11) NOT NULL,
		live_classid INT (11) NOT NULL,
		PRIMARY KEY (memberid, member_referred_id, live_classid),
		referral_date DATETIME NOT NULL
		)";
mysqli_query($link,$query) or die(mysqli_error());



	$query="CREATE TABLE IF NOT EXISTS live_classes_test (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			question_text TEXT NOT NULL,
			live_classid INT (11) NOT NULL,
			status TINYINT (1) NOT NULL DEFAULT 0)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS live_classes_sub_test (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			sub_questions TEXT NOT NULL,
			options TEXT NOT NULL,
			right_option TEXT NOT NULL,
			class_test_id INT (11))";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS test_timing(
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			live_classid INT (11) NOT NULL,
			duration INT (11) NOT NULL,
			pass_mark INT (11) NOT NULL
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS publish_test (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid INT (11) NOT NULL,
			live_classid INT (11) NOT NULL,
			score FLOAT NOT NULL,
			date_taken DATETIME NOT NULL
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS live_classes_test_answers (
			questionid INT (11) NOT NULL,
			memberid INT (11) NOT NULL,
			live_classid INT (11) NOT NULL,
			value TEXT NOT NULL,
			PRIMARY KEY (questionid,memberid),
			FOREIGN KEY (memberid) REFERENCES members(id),
			FOREIGN KEY (questionid) REFERENCES live_classes_sub_test(id),
			)";

	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS news_categories (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			name VARCHAR (50))";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS news_details (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			category_id INT (11) NOT NULL,
			title VARCHAR (255) NOT NULL,
			body MEDIUMTEXT NOT NULL,
			pic VARCHAR (255),
			memberid INT (11) NOT NULL,
			post_date DATETIME NOT NULL)";
	mysqli_query($link,$query) or die(mysqli_error());



	$query="CREATE TABLE IF NOT EXISTS newspics (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			name VARCHAR (255) NOT NULL,
			tag VARCHAR (255) NOT NULL
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS news_comments (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid INT (11) NOT NULL,
			postid INT (11) NOT NULL,
			comment text NOT NULL,
			comment_date DATETIME NOT NULL)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS blog (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			title VARCHAR (255) NOT NULL,
			description MEDIUMTEXT NOT NULL,
			blog_photo VARCHAR (255),
			blog_category VARCHAR (255),
			tags VARCHAR (255),
			upload_date  DATETIME NOT NULL,
			featured TINYINT(1) NOT NULL DEFAULT 0)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS blog_comments (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid INT (11) NOT NULL,
			postid INT (11) NOT NULL,
			comment TEXT NOT NULL,
			comment_date VARCHAR (50))";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS blogpics (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			name VARCHAR (255) NOT NULL,
			tag VARCHAR (255) NOT NULL
			)";
	mysqli_query($link,$query) or die(mysqli_error());


/*
	$query="CREATE TABLE IF NOT EXISTS adminaccess (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			username VARCHAR (55) NOT NULL,
			password VARCHAR (55) NOT NULL
			)";
	mysqli_query($link,$query) or die(mysqli_error());



	$query="CREATE TABLE IF NOT EXISTS email_settings (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id, memberid),
			memberid INT (11) NOT NULL,
			addfriend int NOT NULL,
			friendapproved int NOT NULL,
			internalbox int NOT NULL,
			replytopost int NOT NULL,
			studyclassapproved int NOT NULL,
			testapproved int NOT NULL,
			newsposts int NOT NULL,
			newscomments int NOT NULL,
			joinclassrequest int NOT NULL,
			joinclassapproved int NOT NULL,
			takequiz int NOT NULL,
			postonclasses int NOT NULL,
			postonclassescreated int NOT NULL,
			newnews int NOT NULL,
			newgist int NOT NULL,
			newblog int NOT NULL,
			replystatus int NOT NULL DEFAULT 1
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS inbox (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			senderid INT (11) NOT NULL,
			receiverid INT (11) NOT NULL,
			message text,
			date_sent VARCHAR (255),
			parentid text,
			rec_read int,
			rec_delete int,
			sender_delete int
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS tasks (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid INT (11) NOT NULL,
			email VARCHAR (255) NOT NULL,
			confirmed int NOT NULL,
			subject text,
			message text
			)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS notices (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid INT (11) NOT NULL,
			message text NOT NULL,
			pic text NOT NULL,
			status int NOT NULL,
			date_sent VARCHAR (255)
			)";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS mods (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id, memberid),
			memberid int,
			permit text)";
	mysqli_query($link,$query) or die(mysqli_error());

	$query="CREATE TABLE IF NOT EXISTS mods_pages (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id, page),
			page VARCHAR (255),
			pagetitle VARCHAR (255))";
	mysqli_query($link,$query) or die(mysqli_error());


	$query="CREATE TABLE IF NOT EXISTS logged (
			id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),
			memberid INT (11) NOT NULL,
			timeupdate VARCHAR (255)
			)";
	mysqli_query($link,$query) or die(mysqli_error());
	*/
	/*$query="insert into adminaccess values ('', 'skoola', 'ola')";
		mysql_query($query, $link) or die (mysql_error());

	*/

?>
