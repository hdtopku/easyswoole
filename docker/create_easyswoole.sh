#!/bin/bash
EASYSWOOLE_VERSION="3.4.x"
cd /easyswoole \
    && composer require easyswoole/easyswoole ${EASYSWOOLE_VERSION} \
    && php vendor/bin/easyswoole install
