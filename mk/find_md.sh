#!/bin/sh
# nbt, 16.11.2021

# find markdown files in  all or portions of the web directory tree

if [ "$#" -ne 1 ]; then
  set=default
else
	set=$1
fi

# root dir
cd /pm20/web
folderroot=./folder

case $set in
	default)
		##find -L . -type d \( -path "./tmp" -o -path "./category" -o -path "./folder/*" \) -prune -o -type f -name '*.md' 
    find -L . -regextype posix-extended -maxdepth 4 -type d -regex '\./(tmp|category|folder/.*/[0-9])' -prune -o -type f -name "*.md"
    ;;
  category)
    find ./category -type f -name "*.md"
    ;;
  co | pe)
    find $folderroot/$set -regextype posix-extended -maxdepth 3 -type f -name "*.md"
    ;;

  sh | wa)
    find $folderroot/$set -regextype posix-extended -maxdepth 5 -type f -name "*.md"
    ;;
  co/* | pe/*)
    collection=${set:0:2}
    folder_nk1=${set:3:6}
    hash1=${set:3:4}
    dir=$folderroot/$collection/${hash1}xx/$folder_nk1
    echo $dir
    find $dir -type f -name "*.md"
    ;;
  sh/* | wa/*)
    collection=${set:0:2}
    folder_nk1=${set:3:6}
    hash1=${set:3:4}
    folder_nk2=${set:10:6}
    hash2=${set:10:4}
    dir=$folderroot/$collection/${hash1}xx/$folder_nk1/${hash2}xx/$folder_nk2
    ##echo $collection $hash1 $folder_nk1 $hash2 $folder_nk2 $dir
    find $dir -type f -name "*.md"
    ;;
  *)
    echo "usage: default | category | {collection} | {folder-id}"
    ;;
esac

