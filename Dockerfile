FROM composer
MAINTAINER g8y3e <valentine.pavchuk@ironsrc.com>
RUN mkdir /usr/src/app
ADD . /usr/src/app
WORKDIR /usr/src/app

RUN composer require aws/aws-sdk-php
RUN composer install