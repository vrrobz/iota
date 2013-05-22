create table DeviceTypes (
	id		int		NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name	varchar(255)	NOT NULL,
	endpoint	varchar(255) NOT NULL,
	custom_variables	text	NULL,
	interface	text NOT NULL,
	authentication_template	text	NOT NULL
);

create table Devices (
	id		int		NOT NULL PRIMARY KEY AUTO_INCREMENT,
name	varchar(255)	NOT NULL,
device_type	int	NOT NULL,
authentication	text NOT NULL
);