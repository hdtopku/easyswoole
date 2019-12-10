FROM centos:7

# version defined
ENV SWOOLE_VERSION 4.4.8
# ENV EASYSWOOLE_VERSION 3.x

# update core
RUN yum update -y && \
    yum install -y curl zip unzip  wget openssl-devel gcc-c++ make autoconf && \
    yum install -y epel-release && \
    rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm && \
    yum clean all && \
    yum update -y && \
    yum install -y php72w-devel php72w-openssl php72w-gd php72w-mbstring php72w-mysqli php72w-xml

# 非必需
RUN yum install --skip-broken -y php72w-cli php72w-common php72w-devel php72w-openssl php72w-embedded php72w-fpm php72w-gd php72w-mbstring php72w-mysqli php72w-mysqlnd php72w-opcache php72w-pdo php72w-pecl-redis php72w-xml php72w php72w-bcmath php72w-dba php72w-enchant php72w-imap php72w-interbase php72w-ldap \
    php72w-mcrypt php72w-odbc php72w-pdo_dblib php72w-pear php72w-pecl-apcu php72w-pecl-imagick php72w-pecl-xdebug php72w-pgsql php72w-phpdbg \
    php72w-process php72w-pspell php72w-recode php72w-snmp php72w-soap php72w-tidy php72w-xmlrpc php72w-pecl-igbinary php72w-intl php72w-memcached php72w-pecl-mongodb php72w-pecl-memcached php72w-intl
# 非必需
RUN yum install -y --skip-broken zip unzip git wget make zsh vim epel-release pcre pcre-devel openssl openssl-devel libicu-devel gcc gcc-c++ autoconf libjpeg libjpeg-devel libpng libpng-devel freetype freetype-devel libxml2 libxml2-devel zlib zlib-devel glibc glibc-devel \
    glib2 glib2-devel ncurses ncurses-devel curl curl-devel krb5-devel libidn libidn-devel openldap openldap-devel nss_ldap jemalloc-devel cmake boost-devel bison automake libevent libevent-devel gd gd-devel libtool libmcrypt libmcrypt-devel mcrypt mhash libxslt \
    libxslt-devel readline readline-devel gmp gmp-devel libcurl libcurl-devel openjpeg-devel \
    yum clean all

# composer
RUN curl -sS https://install.phpcomposer.com/installer | php \
    && mv composer.phar /usr/bin/composer \
    && composer self-update --clean-backups \
    && composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ \
    && composer global require hirak/prestissimo

# swoole ext oh-my-zsh inotify(热加载)
RUN wget https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
    cd swoole \
    && phpize \
    && ./configure --enable-openssl \
    && make \
    && make install \
    ) \
    && sed -i "2i extension=swoole.so" /etc/php.ini \
    && rm -r swoole \
    && sh -c "$(curl -fsSL https://raw.github.com/robbyrussell/oh-my-zsh/master/tools/install.sh)" \
    && wget https://pecl.php.net/get/inotify-2.0.0.tgz \
    && tar -xvf inotify-2.0.0.tgz && cd inotify-2.0.0 \
    && phpize \
    && ./configure --with-php-config=/usr/bin/php-config \
    && make && make install \
    && sed -i "3i extension=inotify.so" /etc/php.ini \
    && cd .. && rm -rf inotify-2.0.0*


# Dir
VOLUME /easyswoole

# 无需自动run，手动即可
# CMD [ "/easyswoole/docker/run.sh" ]

# install easyswoole

#RUN cd /easyswoole \
#    && composer require easyswoole/easyswoole=${EASYSWOOLE_VERSION} \
#    && php vendor/bin/easyswoole install



EXPOSE 9501