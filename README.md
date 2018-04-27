# Amazon Web Services S3 Mac to Windows Path & Filename Fixer

In S3 the filename and path is known as the key

e.g. /path/to/filename.txt <== this is the "key"

When copying a file from an Apple Mac to S3 you can have a lot of characters embedded in the key that are incompatible if you wish to download the files to Microsoft Windows

e.g. /path/with */ bad... / characters? for:windows/

You will see the error as "download failed" in aws cli on Windows and the output will show Windows illegal characters in it.

This repo contains a couple of scripts to "rename" (or rekey in S3 parlance) files uploaded to S3 so that they have do not have characters incompatible with windows embedded in either the "folder" or "file" name

## How to Use / About Scripts
command.sh gets an object list from your bucket and pipes it to the first argument you pass it. Then it gnu greps the file to find the windows incompatible characters and creats a second file that only contains the problematic paths / filenames.

Edit command.sh to change --bucket and --prefix values as necessary

e.g.
```bash
aws s3api list-objects --bucket yourBucket --prefix Documents --query 'Contents[].[Key]' --output text > $1
```

Run the shell script with a output filename arg
```bash
./command.sh docs
```
creates two files:

docs - is the list of everything in your s3 bucket

docsout - is the list of all paths that have windows incompatible characters in them

aws-s3-mac2win.php reads docsout to an array and loops through each path and creates a new compatible file and path name. It then escapes the filename arguments ready to pass to the aws cli.

Edit aws-s3-mac2win.php and change bucket, cmd and file

```php
$lines = file('docsout'); # reads the file into an array for looping through

$bucket = 'yourBucketHere';

$cmd = 'aws s3 mv --dryrun '; # dry run before you try for real is a good idea
```

Then you run aws-s3-mac2win.php which will exec aws cli with what ever arguments you configure

```bash
php aws-s3-mac2win.php
```

You need PHP, gnu grep and aws cli installed:

```bash
brew install grep
pip install awscli
```

(not sure how to install PHP it might come installed)

