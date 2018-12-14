<?php

// PW Cast (Password Cast)

$podcast_name = "PW Cast";
$directory = './podcasts';
$file_formats_regexp = "/.*[m4a|mp3]/i";

// scan folder for files

$podcast_dir_files = scandir_sort_by_time($directory);

// scandir() that returns the content sorted by Filemodificationtime
// https://stackoverflow.com/a/11923516
function scandir_sort_by_time($dir) {
    $ignored = array('.', '..', '.gitkeep');

    $files = array();
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) {
          continue;
        }
        $files[filemtime($dir . '/' . $file)] = $file;
    }

    arsort($files);

    return ($files) ? $files : false;
}

// remove non-audio files
$filtered_files = array_filter($podcast_dir_files, function($filename) use($file_formats_regexp) {
  if (preg_match($file_formats_regexp, $filename)) {
    return true;
  }
  return false;
});

// Create feed

$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

header('Content-Type: text/xml; charset=utf-8', true);
echo '<?xml version="1.0" encoding="utf-8"?'.'>'; ?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">
    <channel>
        <title><?php echo $podcast_name ?></title>
        <itunes:summary><?php echo $podcast_name ?></itunes:summary>
        <description><?php echo $podcast_name ?></description>
        <language>en</language>
        <link><?php echo $url ?></link>
        <atom:link href="<?php echo $url ?>" rel="self" type="application/rss+xml"/>
        <?php foreach($filtered_files as $timestamp => $filename): ?>
            <item>
                <title><?php echo $filename ?></title>
                <link><?php echo $filename ?></link>
                <pubDate><?php echo gmdate('D, d M Y H:i:s +0000', $timestamp); ?></pubDate>
                <guid isPermaLink="false"><?php echo $timestamp; ?></guid>
                <enclosure url="<?php echo $url ?>/<?php echo urlencode($filename) ?>" />
                <description><?php echo $podcast_name ?> - <?php echo gmdate('D, d M Y H:i:s +0000', $key); ?></description>
            </item>
        <? endforeach; ?>
    </channel>
</rss>
