# Membership Form

<https://signup.sheffieldhackspace.org.uk/>

made/maintained by @alifeee

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

## What the form should do:

- [x] form should
  - [x] check that required entries are filled (`if(empty($_POST['name'])`)
  - [x] send an email
  - [x] log to a file locally (not long lasting)
  - [x] update an RSS feed or other file accessible to check that the emails are sending properly
- [x] form should (while)
  - [x] explain how to be a keyholder (link to wiki)
  - [x] explain what data we need to legally collect
  - [x] explain what happens to personal data (privacy policy)
    - [x] require consent for processing of personal data
  - [x] explain how to ask any questions (link to wiki and email)
- [x] form should (after)
  - [x] explain what to do next (pay via bank transfer and link to wiki)
    - [x] via content on page
    - [ ] send confirmation via email (should not block confirmation screen as email might fail)
