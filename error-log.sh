#!/bin/bash
rm -r app/cache/dev/*
rm -r app/cache/prod/*
php app/console cache:clear --env=prod && tail -f app/logs/* ~/log/api/error.log
