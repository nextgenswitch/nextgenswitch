UPDATE mysql.user SET Password=PASSWORD('root') WHERE User='nextgenswitch';
DELETE FROM mysql.user WHERE User='';
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';
CREATE DATABASE easypbx;
CREATE USER 'easypbx'@localhost IDENTIFIED BY 'nextgenswitch';
GRANT ALL PRIVILEGES ON easypbx.* TO 'easypbx'@localhost IDENTIFIED BY 'nextgenswitch';
FLUSH PRIVILEGES;

