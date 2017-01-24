FROM php:7.0-cli
MAINTAINER g8y3e <valentine.pavchuk@ironsrc.com>
RUN mkdir /usr/src/app
ADD . /usr/src/app
WORKDIR /usr/src/app

ENV SDK_VERSION 1.5.1