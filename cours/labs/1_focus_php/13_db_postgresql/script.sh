export PGPASSWORD=password
psql -h univ_postgres -U username -d postgres -c "CREATE DATABASE exemple_db;"
psql -h univ_postgres -U username -d exemple_db -f init.sql