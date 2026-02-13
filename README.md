# Quran Database
Database for Holy Quran in multiple SQL formats

![qurandatabase](https://1.bp.blogspot.com/-KUEEmvbbYqM/XrrhnukyH5I/AAAAAAAAORQ/n5KGkW-GikUbP_wGwGQHT3DmB-K-HZD-gCK4BGAsYHg/d/chapters.png)

## Available Formats

This database is available in multiple SQL formats to support different database systems:

- **quran.sql** - Generic SQL format (SQLite compatible)
- **quran_mysql.sql** - MySQL/MariaDB compatible SQL with proper UTF-8 support
- **quran_postgresql.sql** - PostgreSQL compatible SQL
- **quran.sqlite** - Original SQLite database file

## Columns

* ``ID`` - Chapter number (1-114)
* ``NAME_AR`` (Name in Arabic)
* ``NAME_PRON_EN`` (Name pronunciation in English)
* ``CLASS`` (Mecca or Medina)
* ``VERSES_NUMBER`` - Number of verses in the chapter
* ``CONTENT`` - Full Arabic text of the chapter

## Usage

### MySQL/MariaDB
```bash
mysql -u username -p database_name < quran_mysql.sql
```

### PostgreSQL
```bash
psql -U username -d database_name -f quran_postgresql.sql
```

### SQLite
```bash
sqlite3 quran.db < quran.sql
# Or use the provided quran.sqlite file directly
```

## Authors
- This database is made and collected by Bilal Bentoumi

## License
It's totally free, just a good prayer is enough :)
