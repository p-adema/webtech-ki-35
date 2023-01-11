#!/bin/bash
echo "
CREATE USER 'web-read' IDENTIFIED BY '$(cat ../tokens/web-read)';
GRANT SELECT ON app.* TO 'web-read';

CREATE USER 'web-write' IDENTIFIED BY '$(cat ../tokens/web-write)';
GRANT SELECT, UPDATE, DELETE, INSERT ON app.* TO 'web-write';

CREATE USER 'web-deploy' IDENTIFIED BY '$(cat ../tokens/web-deploy)';
GRANT CREATE, ALTER, DROP, INSERT on app.* TO 'web-deploy';

FLUSH PRIVILEGES;"
