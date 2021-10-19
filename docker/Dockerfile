FROM easyswoole/easyswoole3
ENV SWOOLE_VERSION 4.4.23

RUN cd /etc/yum.repos.d && \
    wget -O CentOS-Base.repo http://mirrors.aliyun.com/repo/Centos-8.repo && \
    yum install -y zsh vim git php7.2-json && \
    sh -c "$(curl -fsSL https://raw.github.com/robbyrussell/oh-my-zsh/master/tools/install.sh)" && \
    composer config -g repo.packagist composer https://mirrors.aliyun.com/composer && \
    composer global require hirak/prestissimo

RUN wget https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
    cd swoole \
    && phpize \
    && ./configure --enable-openssl \
    && make \
    && make install )