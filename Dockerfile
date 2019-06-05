FROM ubuntu:18.04

# Install dependencies
RUN apt-get update
RUN apt-get -y install cron
RUN apt-get install -y --no-install-recommends libpq-dev vim nginx php7.2-fpm php7.2-mbstring php7.2-xml php7.2-pgsql

# Copy crontab file to the cron.d directory
COPY crontab /etc/cron.d/crontab

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/crontab

# Apply cron job
RUN crontab /etc/cron.d/crontab

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

# Run the command on container startup
CMD cron && tail -f /var/log/cron.log

# Copy project code and install project dependencies
COPY . /var/www/
RUN chown -R www-data:www-data /var/www/

# Copy project configurations
COPY ./etc/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./etc/nginx/default.conf /etc/nginx/sites-enabled/default
COPY .env_production /var/www/.env
COPY docker_run.sh /docker_run.sh
RUN mkdir /var/run/php

# Start command
CMD sh /docker_run.sh

CMD ["cron", "-f"]
