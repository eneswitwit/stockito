Need this package for npm resources
sudo apt-get install autoconf libtool pkg-config nasm build-essential ffmpeg libav-tools

Required

- php 7.2
- mysql 5.7
- node.js 8+
- npm 5+
- composer
- ProFTPd server (http://www.proftpd.org/) https://www.digitalocean.com/community/tutorials/how-to-configure-proftpd-to-use-sftp-instead-of-ftp#install-proftpd

deployment on local server

- git clone
- cp .env.dist .env
- composer install/update
- php artisan migrate
- php artisan create:admin
- php artisan sync:products


Configs for proftpd and supervisor placed in "configs" folder

ProFTPd
Required modules:
- mod_sql
- mod_quotatab
- mod_sftp (Need ssl certs (Can be self-signed))
