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

for setup in ./courses/*.sql
do
  cat "$setup" >> "../server/scraped.sql"
  rm "$setup"
done

rm -rf ./courses/*
