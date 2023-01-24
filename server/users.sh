#!/bin/bash
echo "
DROP USER IF EXISTS 'web-read', 'web-write', 'web-deploy', 'triggers';

CREATE USER 'web-read' IDENTIFIED BY '$(cat ../tokens/web-read)';
GRANT SELECT ON db.* TO 'web-read';

CREATE USER 'web-write' IDENTIFIED BY '$(cat ../tokens/web-write)';
GRANT SELECT, UPDATE, DELETE, INSERT ON db.* TO 'web-write';

CREATE USER 'web-deploy' IDENTIFIED BY '$(cat ../tokens/web-deploy)';
GRANT CREATE, ALTER, DROP, INSERT on db.* TO 'web-deploy';

CREATE USER 'triggers';
GRANT ALL PRIVILEGES ON db.* TO 'triggers';

FLUSH PRIVILEGES;"
