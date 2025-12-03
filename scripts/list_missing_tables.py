#!/usr/bin/env python3

from pathlib import Path
import re
import json


def extract_tables_from_sql(sql_path: Path):
    pattern = re.compile(r"CREATE TABLE\s+`([^`]+)`", re.IGNORECASE)
    content = sql_path.read_text()
    return set(pattern.findall(content))


def extract_tables_from_migrations(migrations_root: Path):
    pattern = re.compile(r"Schema::create\(\s*['\"]([^'\"]+)['\"]", re.IGNORECASE)
    tables = set()
    for migration in migrations_root.glob("*.php"):
        content = migration.read_text()
        tables.update(pattern.findall(content))
    return tables


def main():
    sql_dir = Path("database/install/parts")
    migrations_dir = Path("database/migrations")

    sql_tables = set()
    for sql_file in sql_dir.glob("*.sql"):
        sql_tables.update(extract_tables_from_sql(sql_file))

    migration_tables = extract_tables_from_migrations(migrations_dir)

    missing = sorted(sql_tables - migration_tables)

    report = {
        "sql_total_tables": len(sql_tables),
        "migration_tables": len(migration_tables),
        "missing_tables_count": len(missing),
        "missing_tables_sample": missing[:20],
    }

    print(json.dumps(report, indent=2))
    Path("scripts/missing_tables_report.txt").write_text(
        "Missing tables ({count}):\n{tables}\n".format(count=len(missing), tables="\n".join(missing))
    )


if __name__ == "__main__":
    main()
