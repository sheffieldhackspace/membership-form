# Membership Form

*not currently used*

![screenshot of form](images/example_form.png)

This form "simply" sends an email with the details to the trustee email. The email manager is then responsible for furthering the information into the membership management system.

## Installation

```bash
# install nginx…

# copy config
sudo cp signup.conf /etc/nginx/sites-available/signup.conf
sudo ln -s /etc/nginx/sites-available/signup.conf /etc/nginx/sites-enabled/signup.conf
sudo nginx -t 
sudo systemctl restart nginx.service

# set up mail
cp .env.example .env
nano .env

# set up log folder
sudo chown www-data:www-data submissions/

# set up cron job to delete logs in log folder
crontab -e
# 0 4 * * * find ./submissions -type f -mtime +13 -exec rm {} \;
```
