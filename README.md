# wp-galdnieki

`login: admin`
`password: test`

### Docker settings

0. Refer to `DOCKER.md`

### WP Setup ###

0. Enable `Doors Post`, `Doors Rest`, `Polylang` plugins
0. Add lv and ru in Polylang
0. Goto Polylang settings and enable `Custom post types and Taxonomies`
0. Goto Polylang settings and disable `Activate languages and translations for media`
0. Goto permalink settings and enable `http://localhost:8080/sample-post/`, this enables `http://localhost:8080/wp-json/` Rest API

### Move files to prod

0. `scp /Users/gropaga/Documents/wp-galdnieki/bin/* max@galdnieks:/home/max/www/wp-galdnieki/bin`