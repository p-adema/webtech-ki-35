#!/bin/bash
echo "
DROP USER IF EXISTS 'web-read', 'web-write', 'functions';

CREATE USER 'web-read' IDENTIFIED BY '$(cat ../tokens/web-read)';
GRANT SELECT ON db.* TO 'web-read';

CREATE USER 'web-write' IDENTIFIED BY '$(cat ../tokens/web-write)';
GRANT SELECT, UPDATE, DELETE, INSERT, EXECUTE ON db.* TO 'web-write';

CREATE USER 'functions';
GRANT ALL PRIVILEGES ON db.* TO 'functions';

FLUSH PRIVILEGES;"
