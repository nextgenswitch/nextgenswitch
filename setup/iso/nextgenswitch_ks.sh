# Example RPM from EPEL
dnf install https://dl.fedoraproject.org/pub/epel/epel-release-latest-9.noarch.rpm -y
dnf -y install https://rpms.remirepo.net/enterprise/remi-release-9.rpm
dnf -y install yum-utils

setsebool -P httpd_can_network_connect=1

dnf install wget zip unzip sox lame redis supervisor git httpd -y
dnf module reset php
dnf module install php:remi-8.2 -y 
dnf install php-{bz2,cli,common,mysql,curl,intl,mbstring,zip,redis,xml} -y
dnf install mariadb-server -y
dnf install iptables-services -y

cd /tmp
git clone  https://github.com/nextgenswitch/nextgenswitch.git
cd nextgenswitch
mv easypbx /var/www/html

systemctl disable firewalld
systemctl mask firewalld

systemctl enable iptables
systemctl enable httpd
systemctl enable supervisord
sudo systemctl enable redis
systemctl enable mariadb

CUSTOM_RPM_URL="http://51.79.230.231:81/office/nextgenswitch-1.0-1.el8.noarch.rpm"

echo "Downloading and installing custom RPM from $CUSTOM_RPM_URL..."
wget -O /tmp/custom.rpm "$CUSTOM_RPM_URL"
if [ $? -eq 0 ]; then
    echo "Download successful, installing..."
    dnf install -y /tmp/custom.rpm
else
    echo "Failed to download custom RPM" >> /root/kickstart-post.log
fi



cd /var/www/html/easypbx
unzip storage.zip
cp .env.example .env
php composer.phar install
chcon -R -t httpd_sys_rw_content_t /var/www/html/easypbx
find /var/www/html/easypbx/public/ -type f -exec chmod 644 {} \;
find /var/www/html/easypbx/public/ -type d -exec chmod 755 {} \;
chmod -R 0777 storage
ln -s ../../../public/sounds storage/app/public/sounds
ln -s ../storage/app/public public/storage
ln -s /var/www/html/easypbx/storage/app/public/records /usr/infosoftbd/nextgenswitch/records
