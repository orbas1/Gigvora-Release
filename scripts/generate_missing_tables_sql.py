#!/usr/bin/env python3

from pathlib import Path
import re


def parse_missing_tables(report_path: Path):
    missing = set()
    if not report_path.exists():
        return missing
    for line in report_path.read_text().splitlines()[1:]:
        line = line.strip()
        if line:
            missing.add(line)
    return missing


def extract_primary_keys(sql: str):
    primary_keys = {}
    for match in re.finditer(
        r"ALTER TABLE `(?P<table>[^`]+)`\s+ADD PRIMARY KEY \((?P<cols>[^)]+)\);",
        sql,
        re.MULTILINE,
    ):
        primary_keys[match.group("table")] = match.group("cols")
    return primary_keys


def main():
    report_path = Path("scripts/missing_tables_report.txt")
    missing_tables = parse_missing_tables(report_path)
    if not missing_tables:
        return

    core_sql = Path("database/install/parts/001_core.sql").read_text()
    primary_keys = extract_primary_keys(core_sql)

    create_pattern = re.compile(
        r"CREATE TABLE `([^`]+)`\s*\((.*?)\)\s*ENGINE[^;]+;",
        re.DOTALL | re.IGNORECASE,
    )

    statements = []
    for match in create_pattern.finditer(core_sql):
        table = match.group(1)
        if table not in missing_tables:
            continue

        body_raw = match.group(2)
        cleaned_lines = []
        for line in body_raw.splitlines():
            line = line.strip()
            if not line:
                continue
            line = line.replace("AUTO_INCREMENT", "")
            line = line.replace(" ON UPDATE CURRENT_TIMESTAMP", "")
            line = re.sub(r"\s+COLLATE\s+[^\s,;]+", "", line, flags=re.IGNORECASE)
            line = re.sub(r"\s+CHARACTER SET\s+[^\s,;]+", "", line, flags=re.IGNORECASE)
            line = re.sub(r"\s+COMMENT\s+'(?:[^']|'')*'", "", line, flags=re.IGNORECASE)
            line = line.replace("UNSIGNED", "")
            cleaned_lines.append(line)

        body = '\n    '.join(cleaned_lines)
        stmt = f"CREATE TABLE IF NOT EXISTS `{table}` (\n    {body}"
        if table in primary_keys and "PRIMARY KEY" not in stmt:
            pk_stmt = primary_keys[table]
            if cleaned_lines and cleaned_lines[-1].endswith(","):
                stmt += f"\n    PRIMARY KEY ({pk_stmt})"
            else:
                stmt += f",\n    PRIMARY KEY ({pk_stmt})"
        stmt += "\n);"
        statements.append(stmt)

    out_path = Path("database/sql/missing_tables.sql")
    out_path.parent.mkdir(parents=True, exist_ok=True)
    out_path.write_text("\n\n".join(statements) + "\n")


if __name__ == "__main__":
    main()
