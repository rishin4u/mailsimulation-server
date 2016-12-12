<?php
//Queries
define('LOGIN_Q',"SELECT * FROM `users` WHERE sha2(concat( '%s',salt ),512) = pass AND login_name='%s'");
define('INSERT_SESSION_Q',"INSERT INTO session (uid,token,time) VALUES(%d,'%s','%s')");
define('FIND_USER_BY_SESSION_Q',
"SELECT U.id  FROM  users U INNER JOIN session S ON S.uid = U.id WHERE token = '%s' AND active=1");

define('INSERT_THREAD_Q',"INSERT INTO mail_thread(subject) VALUES('%s')");
define('INSERT_MAIL_Q',"INSERT INTO mails(thread_id,from_id,message,time,reply_of,forward_of,draft) VALUES(%d,%d,'%s','%s',%d,%d,%d)");
define('INSERT_RECEIVER_Q',"INSERT INTO mail_received(user_id,mail_id,time) VALUES %s");
define('INSERT_RECEIVER_Q_VALUES',"(%d,%d,'%s')");
define('INSERT_ATTACHMENT_Q',"INSERT INTO attachments(mail_id,filepath,filename) VALUES(%d,'%s','%s')");
define('INSERT_FORWARD_ATTACHMENT_Q',"INSERT INTO attachments (filepath,filename,mail_id) SELECT  filepath, filename ,%d FROM attachments WHERE mail_id = %d");
define('GET_MAILS_OF_USER',"SELECT MR.id as rid, is_read, M.message , U.name, U.login_name, M.id as mailid, MT.subject as subject  , MR.time as mailtime  FROM mail_received MR INNER JOIN mails M  ON MR.mail_id = M.id INNER JOIN mail_thread MT on M.thread_id = MT.id INNER JOIN users U on M.from_id  = U.id  WHERE MR.user_id =%d GROUP BY MT.id ");
define('GET_ATTACHMENTS_Q',"SELECT * FROM attachments WHERE mail_id= %d");
define('GET_SENT_Q',"SELECT * FROM mails WHERE from_id= %d AND draft=0");
define('GET_DRAFT_Q',"SELECT * FROM mails WHERE from_id= %d AND draft=1");
define('GET_MAIL_THREAD_Q',"SELECT MT.* FROM mail_thread MT INNER JOIN mails M ON   MT.id= M.thread_id INNER JOIN mail_received MR ON M.id = MR.mail_id WHERE MR.id = %d ");
define('GET_THREAD_MAILS',"SELECT MR.id as rid, is_read, M.message , U.name, U.login_name, M.id as mailid, MT.subject as subject  , MR.time as mailtime  FROM mail_received MR INNER JOIN mails M  ON MR.mail_id = M.id INNER JOIN mail_thread MT on M.thread_id = MT.id INNER JOIN users U on M.from_id  = U.id  WHERE MR.user_id =%d AND MT.id = %d ORDER BY mailtime DESC ");
define('GET_SINGLE_MAIL',"SELECT MR.id as rid, is_read, M.message , U.name, U.login_name, M.id as mailid , from_unixtime(MR.time) as mailtime  FROM mail_received MR INNER JOIN mails M  ON MR.mail_id = M.id INNER JOIN users U on M.from_id  = U.id WHERE MR.id = %d");
?>
