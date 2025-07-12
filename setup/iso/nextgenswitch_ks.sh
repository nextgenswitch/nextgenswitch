# Example RPM from EPEL

setsebool -P httpd_can_network_connect=1

dnf install wget zip unzip sox lame redis supervisor git httpd -y
dnf module reset php
dnf module install php:remi-8.2 -y 
dnf install php-{bz2,cli,common,mysql,curl,intl,mbstring,zip,redis,xml,bcmath,process} -y
dnf install mariadb-server -y
dnf install iptables-services -y



systemctl disable firewalld
systemctl mask firewalld

systemctl enable iptables
systemctl enable httpd
systemctl enable supervisord
sudo systemctl enable redis
systemctl enable mariadb



dnf install -y easypbx/setup/iso/nextgenswitch-1.1-1.el8.noarch.rpm

cp easypbx/setup/iso/nextgenswitch_setup.sh /usr/local/bin/
chmod +x /usr/local/bin/nextgenswitch_setup.sh
sudo restorecon -v /usr/local/bin/nextgenswitch_setup.sh

cp easypbx/setup/iso/nextgenswitch_setup.service /etc/systemd/system/
sudo chmod 644 /etc/systemd/system/nextgenswitch_setup.service
sudo restorecon -v /etc/systemd/system/nextgenswitch_setup.service

sudo systemctl enable nextgenswitch_setup.service

cp -fr easypbx /var/www/html/

cd /var/www/html/easypbx
unzip storage.zip
cp .env.example .env
php composer.phar install
chcon -R -t httpd_sys_rw_content_t /var/www/html/easypbx
find /var/www/html/easypbx/public/ -type f -exec chmod 644 {} \;
find /var/www/html/easypbx/public/ -type d -exec chmod 755 {} \;
chmod -R 0777 storage
#ln -s ../../../public/sounds storage/app/public/sounds
#ln -s ../storage/app/public public/storage
#ln -s /var/www/html/easypbx/storage/app/public/records /usr/infosoftbd/nextgenswitch/records
