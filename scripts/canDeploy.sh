#!/bin/bash
cd ..
# Run npm to see if all JS and CSS compile ok
npm run dev

# Need to have the local server running for dusk tests
php artisan serve &
php artisan dusk

vendor/bin/phpunit


