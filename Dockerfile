FROM php:8.2-apache

# Copy code vào container
COPY . /var/www/html/

# Enable mod_rewrite (quan trọng cho Laravel hoặc URL đẹp)
RUN a2enmod rewrite

# Thiết lập quyền và phân quyền thư mục
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
