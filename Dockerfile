FROM linode/lamp
LABEL maintainer "achyutapiyush@gmail.com"

RUN apt-get update
RUN apt-get install -f -y phpmyadmin lamp-server^
RUN /var/www/FamilyTree

ADD ./* /var/www/FamilyTree/


EXPOSE 80
