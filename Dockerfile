FROM linode/lamp
LABEL maintainer "piyushparkash"

RUN apt-get update
RUN apt-get install -f -y phpmyadmin lamp-server^
RUN /var/www/FamilyTree

ADD ./* /var/www/FamilyTree/


EXPOSE 80
