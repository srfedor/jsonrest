# jsonrest

CRUD operations on MySQL tables, by sending JSON commands in POST/GET parameter.

Example:
index.php?json={"table": "booking", "cmd": "insert", "set": {"tour_id":1, "qty":20}}
More examples in tests.
Example.sql is used for dropping and creating tables before tests.

See index.php for whitelist and conf.php for database configuration.

