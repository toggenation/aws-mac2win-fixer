This is a couple of scripts to rename files uploaded to S3 that have characters incompatible with windows embeded in either the folder or file name

command.sh gets an object list from your bucket and pipes it to the first argument you pass it. Then it gnu greps the file to find the windows incompatible characters and creats a second file that only contains the problematic paths / filenames.

e.g.

./command.sh docs

creates 

docs
docsout

aws-s3-mac2win.php reads docsout to an array and loops through each path and creates a new compatible file and path name. It then escapes the filename arguments ready to pass to the aws cli.

You need PHP, gnu grep and aws cli installed:
brew install grep
pip install awscli

(not sure how to install php it might come installed)

