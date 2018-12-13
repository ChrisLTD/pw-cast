<?php

// PW Cast (Password Cast)

// scan folder for files
// remove non-audio files
// create feed

$podcast_dir_files = scandir('podcasts');

echo "<h1>Hello, world</h1>";

echo "<pre>" . print_r($podcast_files, true) . "</pre>";