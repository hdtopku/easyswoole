#!/usr/bin/env bash
SWOOLE_VERSION=4.4.8
ENV_PATH='/root/test'
FILE="/easyswoole/easyswoole"
cd /etc/yum.repos.d && mv CentOS-Base.repo CentOS-Base.repoe.bak
wget -O CentOS-Base.repo http://mirrors.aliyun.com/repo/Centos-7.repo

yum install -y zsh vim git

cd /easyswoole
# remove xdebug
# yum remove -y php72w-pecl-xdebug
if [ ! -f "$FILE" ]; then
  docker/create_easyswoole.sh
fi
composer dumpautoload
if [ -f "$FILE" ]; then
  php easyswoole start
fi
