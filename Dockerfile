FROM centos:6
MAINTAINER itspoma <itspoma@gmail.com>

# ENV home /shared

# WORKDIR ${home}
# ADD ./shared/site $home

#
RUN true \
    && yum clean all \
    && yum install -y git curl mc \
    && yum install -y mysql mysql-server \
    && yum install -y httpd

# php 5.5
RUN true \
    && rpm -Uvh http://mirror.webtatic.com/yum/el6/latest.rpm \
    && yum install -y php55w php55w-pdo php55w-mysql php55w-intl

# configure the php.ini
RUN echo "" >> /etc/php.ini \
 && sed 's/;date.timezone.*/date.timezone = Europe\/Kiev/' -i /etc/php.ini \
 && sed 's/^display_errors.*/display_errors = On/' -i /etc/php.ini \
 && sed 's/^display_startup_errors.*/display_startup_errors = On/' -i /etc/php.ini

# configure the httd.conf
RUN sed 's/#ServerName.*/ServerName demo/' -i /etc/httpd/conf/httpd.conf
RUN sed 's/#EnableSendfile.*/EnableSendfile off/' -i /etc/httpd/conf/httpd.conf

# put vhost config for httpd
ADD ./config/httpd/site.conf /etc/httpd/conf.d/site.conf

# install Composer
RUN curl -sS https://getcomposer.org/installer | php \
 && mv composer.phar /usr/local/bin/composer

RUN true

WORKDIR /shared/site

# add startup shell scripts
ADD ./config/init.sh /tmp/init.sh

CMD ["/bin/bash", "/tmp/init.sh"]