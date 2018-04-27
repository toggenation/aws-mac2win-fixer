# get a list of objects in your bucket
aws s3api list-objects --bucket yourBucket --prefix Documents --query 'Contents[].[Key]' --output text > $1

# check for the illegal characters in the list and pipe to file 
# mac grep is limited I prefer gnu grep
# cat $1 | grep -e '*' -e '|' -e '<' -e '>' -e '\\' -e '?' -e '"' -e ':' > ${1}out

# gnu grep on mac with multiple perlre mawr power
# install gnu grep using 'brew install grep'
# find 
# star *
# pipe |
# gt >
# lt <
# backslash \
# question mark ?
# double quote "
# colon :
# trailing spaces in path /example /
# leading spaces in path / example/
# trailing dots in path /example.../

cat $1 | ggrep -P '\*|\||<|>|\\|\?\"|:|\s+/|/\s+|\.+/' > ${1}out
