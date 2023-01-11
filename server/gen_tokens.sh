#!/bin/bash
pick_random() { echo -n "${1:RANDOM%${#1}:1}"; }

function random_token() {
    {
      for _ in $( seq 1 "$1" )
        do
           pick_random '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
        done
    }
}

rm -rf tokens
mkdir tokens
cd tokens || exit

touch hostname
echo -n '0.0.0.0' >> hostname

touch web-read
random_token 64 >> web-read

touch web-write
random_token 64 >> web-write

touch web-deploy
random_token 64 >> web-deploy
