############################################################
# Dockerfile to build Nginx Installed Containers
# Based on Ubuntu
############################################################

# Set the base image to Ubuntu
FROM ubuntu:17.10

# Install Nginx

# Add application repository URL to the default sources
#RUN echo "deb http://archive.ubuntu.com/ubuntu/ raring main universe" >> /etc/apt/sources.list

# Update the repository
RUN apt-get update

# Install necessary tools
RUN apt-get install -y nano wget dialog net-tools

# Download and Install Nginx
RUN apt-get install -y nginx

# Download and Install PHP
RUN apt-get install -y php7.1

ADD . /var/www/stockito


# Remove the default Nginx configuration file
# RUN rm -v /etc/nginx/nginx.conf

# Copy a configuration file from the current directory
# ADD nginx.conf /etc/nginx/

# Copy a configuration file from the current directory
ADD site.conf /etc/nginx/sites-enabled/

# Append "daemon off;" to the beginning of the configuration
# RUN echo "daemon off;" >> /etc/nginx/nginx.conf

# Expose ports
EXPOSE 80

# Set the default command to execute
# when creating a new container
CMD service nginx start