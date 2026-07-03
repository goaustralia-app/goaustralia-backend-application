Here i have my invitation rounds table ready . i have 2 tables invitation rounds and round_details. I need to scrape the invitation rounds data from an external public site webpage. 
Page url: https://immi.homeaffairs.gov.au/visas/working-in-australia/skillselect/invitation-rounds 
from this page i want to scrape the data of different rounds. 
Text to find: Current round
Invitations issued on 4 June 2026

this a round data and this text menas all the roudn details are these under: Invitations issued by occupation and minimum score invited . there will be a table scrape the data nd keep that data into our db. 
Occupation - minimum points score. 

build a data scrapper to get the data from the webpage and build a format to push the data into our invitation_rounds table. 
Build a web scraper for the Australian Home Affairs SkillSelect invitation rounds page.

Page URL:
https://immi.homeaffairs.gov.au/visas/working-in-australia/skillselect/invitation-rounds

Requirement:
Scrape invitation round data from the public webpage and save it into our database.

Database tables:

1. invitation_rounds
2. round_details

Scraping logic:

* Find each invitation round section from the page.
* Identify the round title/date text, for example:
  “Current round”
  “Invitations issued on 4 June 2026”
* Treat this as one invitation round.
* Under each round, find the table titled:
  “Invitations issued by occupation and minimum score invited”
* From that table, scrape the following columns:

  * Occupation
  * Minimum points score

Data mapping:

* Save the round-level data into invitation_rounds table.
* Save each occupation row under that round into round_details table.
* round_details should be linked with invitation_rounds using invitation_round_id.

Expected structure:

invitation_rounds:
see the table structure

round_details:
see the table structure


Implementation requirements:

* Build the scraper in Laravel.
* Create an Artisan command to run the scraper manually or by cron.
* Avoid duplicate round insertion by checking invitation_date before inserting.
* If the round already exists, update the related round_details data.
* Add proper error handling and logging.
* The scraper should be reusable for future invitation rounds.
* Use HTTP client and DOM parser or a suitable scraping package.
* Keep the code clean, structured, and production-ready.

Expected output:

* Laravel migration update if needed
* Model relationships
* Scraper service class
* Artisan command
* Example cron setup
* Insert/update logic for invitation_rounds and round_details
