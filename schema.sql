create table member
{
id int(11) auto_increment,
membername mediumtext not null,
username mediumtext default null,
password mediumtext default null,
sonof int(11) not null,
profilepic mediumtext default null,
dob int(11) default null,
gender int(1) default 0,
relationship_status int(1) default 0,
gaon mediumtext default null,
related_to int(11) default null,
emailid mediumtext default null,
alive int(1) default 0,
aboutme longtext default null,
lastlogin int(11) default null,
joined int(11) default null,
approved int(1) default 0,
tokenforact mediumtext,
primary key (id)
}

create table joinrequest
{
id int(11) auto_increment not null,
formember int(11) not null,
pic mediumtext default null,
personalmessage longtext default null,
emailid mediumtext not null,
tokenforact mediumtext not null,
approved int(1) default 0,
primary key (id)
}

create table feedback
{
id int(11) auto_increment,
user_name mediumtext not null,
user_emailid mediumtext not null,
feedback_text longtext not null,
seen int(1) default 0,
primary key (id)
}

create table suggested_table
{
id int(11) auto_increment,
typesuggest mediumtext not null,
suggested_value mediumtext not null,
suggested_by int(11) not null,
ts int(11) not null,
primary key (id)
}


create table talking
{
id int(11) auto_increment,
touser int(11) not null,
fromuser int(11) not null,
attime int(11) not null,
replyto int(11) not null,
themessage longtext not null,
primary key (id)
}
