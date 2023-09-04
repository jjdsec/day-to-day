FROM nginx:latest
FROM php:8-fpm
FROM mysql:latest

# RUN docker-php-ext-install bcmath bz2 calendar  dba exif gettext iconv intl  soap tidy xsl zip&&\
#     docker-php-ext-install mysqli pgsql pdo pdo_mysql pdo_pgsql  &&\
#     docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp &&\
#     docker-php-ext-install gd