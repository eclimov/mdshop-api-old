FROM nginx:alpine

WORKDIR "/application"

COPY . /application
COPY /docker/dev/nginx/nginx.conf /etc/nginx/conf.d/default.conf
