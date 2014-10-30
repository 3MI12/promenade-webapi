#!/bin/bash
php app/console cache:clear --env=prod && tail -f app/logs/* ~/log/api/error.log
