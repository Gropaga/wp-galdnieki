###How to connect to MySQL
1. Run `docker-compose up` - create image from container
2. Run `docker network ls` - check network
3. Find current line `fac248aebe86        wp-galdnieki_default   bridge              local` where `fac248aebe86` is the network id
4. Run `docker inspect fac248aebe86` - find `"Name": "wp-galdnieki_mysql_1"`
5. Copy IPAddress `172.18.0.3` or name `wp-galdnieki_mysql_1` into required MySQL config in `wp-galdnieki/src/.env`