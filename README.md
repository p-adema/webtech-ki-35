# Webtech project by KI-35
By: Peter, Ruben, Gilles, Jesse

### Build the docker image with:
`docker-compose up --build`
    (might need `sudo docker-compose up --build`)

### Access the server at:

http://localhost

### Kill the server with

`Ctrl` + `C` in the terminal you ran `docker-compose up`

### Rerun the server with

`docker-compose up` (might need `sudo docker-compose up`)

## Hard reset docker:

`docker images` -> find `web-tech-sql` and `web-tech-php` column `IMAGE-ID`

`docker rmi <IMAGE-ID>` will error, with at the end a `CONTAINER-ID`

`docker rm <CONTAINER-ID` outputs the container ID

`docker rmi <IMAGE-ID>` again, should succeed now

Repeat for both `web-tech-sql` and `web-tech-php`

Start docker server
