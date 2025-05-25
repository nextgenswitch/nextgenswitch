-- Set root password (MySQL 5.7+ and 8.0+)
ALTER USER 'root'@'localhost' IDENTIFIED BY 'nextgenswitch';

-- Remove anonymous users
DELETE FROM mysql.user WHERE User='';

-- Remove remote root access
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');

-- Remove test database and privileges
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';

-- Create easypbx database if not exists
CREATE DATABASE IF NOT EXISTS easypbx;

-- Recreate easypbx user
DROP USER IF EXISTS 'easypbx'@'localhost';
CREATE USER 'easypbx'@'localhost' IDENTIFIED BY 'easypbx';

-- Grant privileges to easypbx user
GRANT ALL PRIVILEGES ON easypbx.* TO 'easypbx'@'localhost';

-- Apply all changes
FLUSH PRIVILEGES;

