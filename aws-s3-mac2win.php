<?php

$lines = file('docsout'); # reads the file into an array for looping through

$bucket = 'yourBucketHere';

$s3 = 's3://' . $bucket . '/';

# edit to your taste good idea to do dry run first
#$cmd = 'aws s3 mv --recursive ';

# --recursive is good for renaming a top level dir and rehome it's children
# e.g. /* funcky ?? folder : name... / ==> /funcky folder name/
$cmd = 'aws s3 mv --dryrun '; # not really

foreach ($lines as $line) {
    $trimmed = trim($line);
    $arg1 = escapeshellarg($s3 . $trimmed);
    $arg2 = escapeshellarg($s3 . filterFilename($trimmed));
    $fullCommand = $cmd . $arg1 . ' ' . $arg2;
    echo $fullCommand . "\n";
    $ret = shell_exec($fullCommand);
    echo $ret . "\n";
}

/**
 * filterFilename
 * @param string $name the file name string
 * @return string
 */
function filterFilename($name)
{
    // remove illegal file system characters https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words
    $name = str_replace(array_merge(
        array_map('chr', range(0, 31)),
        array('<', '>', ':', '"', '\\', '|', '?', '*')
    ), '', $name);
    // maximise filename length to 255 bytes http://serverfault.com/a/9548/44086
    // $ext = pathinfo($name, PATHINFO_EXTENSION);
    //$name= mb_strcut(pathinfo($name, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($name)) . ($ext ? '.' . $ext : '');
    // mixed preg_replace ( mixed $pattern , mixed $replacement , mixed $subject [, int $limit = -1 [, int &$count ]] )
    $name = preg_replace('/\s+\//', '/', $name); # removes traling space eg /path with trailing space  /
    $name = preg_replace('/\/\s+/', '/', $name); # removes leading spaces eg / path with leading spaces/
    $name = preg_replace('/\.+\//', '/', $name); # removes trailing dots eg /path with dots.../

    return $name;
}
