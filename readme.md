### Group challenge with Léa, Victoria and Tessa

This repository contains the group challenge Workflow 
<br>[Trello overview](https://trello.com/b/wjcTfNIf/group-challenge-ticket-system)

## How to install - setup vhost
```
VirtualHost *:80>
ServerName challenge-workflow.local

DocumentRoot /var/www/challenge-workflow/public
DirectoryIndex /index.php
<Directory /var/www/challenge-workflow/public>
    AllowOverride All
    Order Allow,Deny
    Allow from All
    FallbackResource /index.php
</Directory>
</VirtualHost>
```

## Installing the project

After cloning this repo, run
 - "composer install" inside the root directory
 For the function reset password, run
 - composer require symfony/webpack-encore-bundle
 - composer require symfony/postmark-mailer
 - composer require symfony/sendgrid-mailer
 
## Creating the database
 - Make a copy of .env to .env.local and change DATABASE_URL parameter with your database configuration. (don't commit this file!)
 
## Installing the database.
- Create a new database "ticketsystem".
- Link with database, use the command php bin/console doctrine:migrations:migrate

! Don't modify these folders (part of the composer)
 - vendor 
 - node_modules

[Instructions Readme](https://github.com/becodeorg/atw-giertz-3-23/tree/master/3.The-Mountain/2.symfony/5.group-project)
## Title: workflow

- Repository: `challenge-workflow`
- Type of Challenge: `Consolidation Challenge`
- Duration: `5 days`
- Deployment strategy : `heroku`
- Team challenge : `group`

## Learning objectives

## The Mission
Until now we have mainly been writing CRUD applications, where the main focus is on storing and display data.

Another big part of development is workflow implementation to automate processes in the real world. Any time you have a status field (for example is an invoice paid? Is a product delivered?) you probably have a workflow before you.

## Must-have features
This week we are going to implement the workflow of a call center. 

We have 5 different users in our system that have different abilities:

### Guest
- [x] Can register himself to become a Customer.
- [x] Can login to become another role (based on credentials)
- [x] Provide a "forgot my password" functionality

! [register screenshot](screenshots/register.png)

### Customer
- [x] A customer can create a ticket on the site. It get's the status "open".
- [x] The customer can see all open tickets that he created and comment on them.
- If the customer responds to a ticket with status "Waiting for customer feedback" mail the agent and change the status again to "in progress".
- [x] A customer can reopen a ticket if it has been closed for less than 1 hour.

### Agent
- [x] First line agents can see all open tickets and assign one to themselves. It now get's the status "in progress".
- [x] Agents can leave comments on a ticket which can be public (the customer can see the comment and react) or private (invisible for customer)
- [x] If the agent leaves a public comment mark the ticket "Waiting for customer feedback"
- [x] First line agents can "escalate a ticket" to "second line" help.
- [x] An agent can close a ticket if it has at minimum one agent comment (to explain the solution to the customer).

### Second line agent
- [x] Second line agents can do everything a first line agent can do, but only for escalated tickets.

### Manager
- A manager can create new agents or change the details of an agent (first or second line help). When a new agent is created sent a welcome e-mail to the agent, with a link where the agent can configure his password. You could reuse logic of the "forgot my password" guest flow here.
- Provide a dashboard where managers can see statistics about the agents:
    - [x] Number of open tickets
    - [x] Number of closed tickets
    - [x] Number of tickets that where reopened
    - [x] A percentage comparision between the 2 numbers above.
- [x] A manager can re-assign tickets or mark them as "won't fix". In the last case the ticket is considered closed and cannot be opened by the customer later on. You should provide a required field to enter a reason for the manager why he will not fix it.

- [x] Managers can with one button de-assign all tickets, they once again get the status "open".
They normally do this at the end of every working day to prevent tickets being locked by an agent who is sick the next day.
- [x] Managers can assign priorities, on which the tickets should be sorted.

! [dashboard screenshot](screenshots/dashboard.png)

### General rule
- Every time a ticket is updated (comment, status change) you have to mail the customer EXCEPT when a private comment is made.

## Nice to have features
- Your imagination is the limit!
