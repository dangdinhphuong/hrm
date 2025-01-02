FROM 192.168.1.205/images-base/php-base:8.1.5-apache as base


COPY ./scripts/supervisor_worker.conf /etc/supervisor/conf.d/worker.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./scripts/start.sh /start.sh
RUN chmod 755 /start.sh && chmod u+x /start.sh && chmod a+x /start.sh
WORKDIR /var/www/html
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public \
    TZ='Asia/Ho_Chi_Minh' \
    APACHE_RUN_USER=www-data \
    APP_NAME=SalePortal \
    APP_ENV=development \
    APP_KEY=base64:lJ5gljd+XGljrqXYk23V/1ZaUgh+LKAmWnVbGHZKMAA= \
    APP_URL=http://localhost \
    LOG_CHANNEL=daily \
    DB_CONNECTION=mysql \
    DB_HOST=192.168.1.248 \
    DB_READ_PORT=3306 \
    DB_WRITE_PORT=3306 \
    DB_DATABASE=educa_crm \
    DB_USERNAME=mysql_crm_edu \
    DB_PASSWORD=BKiS2jiL6H52BABg \
    APP_ROLE_SUPER_ADMIN=super-admin \
    VITE_APP_ROLE_SUPER_ADMIN=super-admin

RUN chown -R www-data:www-data /var/www
#USER www-data
COPY --chown=www-data:www-data . /var/www/html
RUN composer install
RUN npm install
RUN npm run build
RUN php /var/www/html/artisan storage:link
#RUN composer dump-autoload --optimize
EXPOSE 80

# Run Apache service
CMD ["/usr/sbin/apache2ctl", "-DFOREGROUND"]
