[FamilyTree](http://vanshavali.ratupar.in) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/piyushparkash/FamilyTree/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/piyushparkash/FamilyTree/?branch=master)
========================================================================================================================================================================

**[FamilyTree](http://vanshavali.ratupar.in)** is simple PHP Application targetting the collection of family data which can viewed with beautiful visualisation. You can add/update data, play with the visualisation, show you children their roots, add new members as they enter your family.


Installation
============

1. First thing is to clone the repository. If you are not familiar with the word clone, it just means to download the application. You can download it from [here](https://github.com/piyushparkash/FamilyTree/archive/develop.zip), else if you know how to clone, below is the command you can use to clone the repository.

		$ git clone https://github.com/piyushparkash/FamilyTree.git


2. After this step you have to move the url of the server where you have place the source after the first step. Let's assume that you are installing this application locally say in /var/www/FamilyTree. Then go to `localhost/FamilyTree`. 

3. After this if you see output from the browser apart from 404 Not Found error, then you are in right path. You must see something saying, Please provide permissions to some directories. Please use the below command to give permission 

		$ chmod -R 744 directory_name/

Few directories in FamilyTree need write permissions which are not there by default. You will have to provide that explicitly.

4. After the 3rd step it's pretty much self explainatory. But if you still need help, I am mentioning the steps. First thing that **FamilyTree** will be doing would be to setup the database. So you need to provide the database credentials. I am mentioning the default values. It might be different in your systems.
	
	`Host: localhost`
	
	`Username: root`
	
	`Password: your-password`
	
	`Database: mention any name`

5) If everything goes right, It will install FamilyTree with sample data in it and you will be able to see the Family with the visualisation.

Facing Problems?
-----------------------

We are so sorry that you are facing problems.

Report it [here](https://github.com/piyushparkash/FamilyTree/issues) so that we can act on it immediately.

Get Involved / Contribute
-------------------------

Visit this [website](http://vanshavali.ratupar.in) 

AUTHORS:
--------

Piyush Parkash

Official Website: [vanshavali.ratupar.in](http://vanshavali.ratupar.in)

Email: achyutapiyush@gmail.com
