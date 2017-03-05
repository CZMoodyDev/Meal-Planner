# Meal-Planner
In-house tool for purchasing and creating frozen meals

This tool does not include the includes directory that has the connection information...but it uses a locally hosted mySQL database. 

It uses one table with these fields:
* rid (Primary key, recipe ID)
* instructions (Stored as a long string)
* ingredients (Stored as a json string)
* name

In the future it would be nice to automate ingredient creation to not need preformatted json. I don't plan on making this very secure since its hosted in-house. If I did though, there would need to be some serious scrubbing for potential SQL injections. Also the information read from the database should be sanitized if it were used out-of-home.
