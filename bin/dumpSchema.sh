#!/bin/bash
mysqldump -u root -d recipes | sed 's/AUTO_INCREMENT=[0-9]*\b//'
