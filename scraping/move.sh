#!/bin/bash
for video in ./courses/*/*.mp4
do
  IFS='/' read -ra name <<< "$video"
  mv "$video" "../pages/resources/videos/${name[3]}"
done

for thumbnail in ./courses/*/*.jpg
do
  IFS='/' read -ra name <<< "$thumbnail"
  mv "$thumbnail" "../pages/resources/thumbnails/${name[3]}"
done

for videos in ./courses/*.videos.sql
do
  cat "$videos" >> "../server/scraped.sql"
  rm "$videos"
done

#for users in ./courses/*.users.sql
#do
#  cat "$users" >> "../server/scraped/users.sql"
#  rm "$users"
#done
#
#for comments in ./courses/*.comments.sql
#do
#  cat "$comments" >> "../server/scraped/comments.sql"
#  rm "$comments"
#done

#rm -rf ./courses/*
