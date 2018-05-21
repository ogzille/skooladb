<?php
require_once "Database_Connect.php";

	$query="create table if not exists members (
			id int not null auto_increment, primary key(id),
			firstname varchar(255) not null,
			lastname varchar(255) not null,
			email varchar(255),
			slug varchar(255) not null,
			password varchar(255),
			registration_date datetime  not null,
			activation_key varchar(255) not null,
			confirmed tinyint(1) not null default 0,
			suspended tinyint(1) not null default 0,
			gender char(1) not null,
			show_gender char(1) not null,
			birthdate varchar(50) not null,
			show_birthdate int not null,
			phone varchar(20),
			country_id tinyint(3),
			timezone varchar(50),
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
			status tinyint not null

			)";
	//status: 1 - open, 2 - locked and free, 3 - locked and paid
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists classes_fees (
			id int not null auto_increment, primary key(id),
			classid int(11) not null,
			amount float not null,
			currency varchar(10),
			discount int not null default 0
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
			primary key(memberid, classid),
			FK memberid references members(id)
				ON UPDATE CASCADE
				ON DELETE CASCADE,
			FK classid references classes(id)
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
			classname varchar(255) not null,
			description text not null,
			logo varchar(255),
			date_created varchar(40) not null,
			createdby int not null,
			confirmed int not null,
			status tinyint not null

			)";
	//status: 1 - open, 2 - locked and free, 3 - locked and paid
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

	$query="create table if not exists root_aptitude (
			Id int not null auto_increment, primary key(id),
			Question_Text text not null,
			Question_Category varchar(50) not null,
			suspended int)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists sub_aptitude (
			Id int not null auto_increment, primary key(id),
			Sub_Questions text not null,
			Options text not null,
			Right_Option text not null,
			Parent_Id int)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists test_timing(
			id int not null auto_increment, primary key(id),
			classid int not null,
			number_of_questions varchar(255),
			duration varchar(255)
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

	$query="create table if not exists news_jokes (
			id int not null auto_increment, primary key(id),
			categoryid int not null,
			title text not null,
			body text not null,
			pic varchar(255),
			date varchar(60) not null,
			whichgroup varchar(20))";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists gist_categories (
			id int not null auto_increment, primary key(id),
			categoryname varchar(255))";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists gist_details (
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

	$query="create table if not exists gistpics (
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

	$query="create table if not exists gist_comments (
			id int not null auto_increment, primary key(id),
			memberid int not null,
			postid int not null,
			comment text not null,
			commentdate varchar(50))";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists jobs (
			id int not null auto_increment, primary key(id),
			title varchar(255) not null,
			description text not null,
			email varchar(255),
			website varchar(255),
			logo  varchar(255),
			howto int,
			job_category varchar(255),
			tags varchar(255),
			advert_date varchar(255),
			expiry_date varchar(255),
			upload_date  varchar(255),
			featured int not null)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists company_logos (
			id int not null auto_increment, primary key(id),
			name varchar(255) not null,
			tag varchar(255) not null
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists public_jobs (
			id int not null auto_increment, primary key(id),
			name varchar(255) not null,
			company_name varchar(255) not null,
			email varchar(255) not null,
			website varchar(255),
			location varchar(255) not null,
			description text,
			date_sent varchar(100))";
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

	$query="create table if not exists comments_table (
			id int not null auto_increment, primary key(Id),
			title varchar(255) not null,
			comment text not null,
			sender int not null,
			pic_attached varchar(255),
			date_sent varchar(255) not null,
			parentid varchar(255),
			classid varchar(255)
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists adminaccess (
			id int not null auto_increment, primary key(id),
			username varchar(55) not null,
			password varchar(55) not null
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists comments_rate (
			id int not null auto_increment, primary key(id),
			commentid varchar(255),
			rates text
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists publish_test (
			id int not null auto_increment, primary key(id),
			memberid int not null,
			classid int not null,
			score int not null,
			date_taken varchar(255)
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

	$query="create table if not exists statusupdate (
			id int not null auto_increment, primary key(id),
			memberid int not null,
			parentid int not null,
			comment text,
			date_updated varchar(255)
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists statusbroadcast (
			id int not null auto_increment, primary key(id),
			statusid int not null,
			sender int not null,
			date_broadcast varchar(255) not null
			)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists status_rate (
			id int not null auto_increment, primary key(id),
			commentid varchar(255),
			rates text
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

	$query="create table if not exists chatroom (
			id int not null auto_increment, primary key(id),
			memberid varchar(25) not null,
			classid varchar(25) not null,
			date varchar(60) not null,
			body text not null)";
	mysql_query($query,$link) or die(mysql_error());

	$query="create table if not exists onlineroom (
			id int not null auto_increment, primary key(id),
			memberid varchar(25) not null,
			classid varchar(25) not null,
			flag varchar(60) not null,
			entrytime varchar(60) not null)";
	mysql_query($query,$link) or die(mysql_error());

	/*$query="insert into adminaccess values ('', 'skoola', 'ola')";
		mysql_query($query, $link) or die (mysql_error());

	*/

?>
