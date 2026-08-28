#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SOURCE_DIR="$ROOT_DIR/application/"
TARGET_DIR="$ROOT_DIR/kiustore_apps/"

usage() {
  cat <<'USAGE'
Usage:
  ./scripts/sync_application_to_kiustore_apps.sh [--dry-run|--verify]

Modes:
  default    Mirror application/ into kiustore_apps/ using rsync --delete.
  --dry-run  Show files that would be added, updated, or deleted.
  --verify   Verify both folders have identical file paths and checksums.
USAGE
}

verify() {
  local tmp_dir source_list target_list common_list only_source only_target different
  tmp_dir="$(mktemp -d)"
  source_list="$tmp_dir/application_files.txt"
  target_list="$tmp_dir/kiustore_apps_files.txt"
  common_list="$tmp_dir/common_files.txt"
  only_source="$tmp_dir/only_application.txt"
  only_target="$tmp_dir/only_kiustore_apps.txt"
  different="$tmp_dir/different_files.txt"

  find "$SOURCE_DIR" -type f | sed "s#^$SOURCE_DIR##" | sort > "$source_list"
  find "$TARGET_DIR" -type f | sed "s#^$TARGET_DIR##" | sort > "$target_list"
  comm -23 "$source_list" "$target_list" > "$only_source"
  comm -13 "$source_list" "$target_list" > "$only_target"
  comm -12 "$source_list" "$target_list" > "$common_list"
  : > "$different"

  while IFS= read -r file_path; do
    local source_hash target_hash
    source_hash="$(shasum -a 256 "$SOURCE_DIR$file_path" | awk '{print $1}')"
    target_hash="$(shasum -a 256 "$TARGET_DIR$file_path" | awk '{print $1}')"
    if [[ "$source_hash" != "$target_hash" ]]; then
      printf '%s\n' "$file_path" >> "$different"
    fi
  done < "$common_list"

  printf 'application files: %s\n' "$(wc -l < "$source_list" | tr -d ' ')"
  printf 'kiustore_apps files: %s\n' "$(wc -l < "$target_list" | tr -d ' ')"
  printf 'only in application: %s\n' "$(wc -l < "$only_source" | tr -d ' ')"
  printf 'only in kiustore_apps: %s\n' "$(wc -l < "$only_target" | tr -d ' ')"
  printf 'different content: %s\n' "$(wc -l < "$different" | tr -d ' ')"

  if [[ -s "$only_source" || -s "$only_target" || -s "$different" ]]; then
    printf '\nVerification failed.\n'
    if [[ -s "$only_source" ]]; then
      printf '\nOnly in application:\n'
      sed -n '1,200p' "$only_source"
    fi
    if [[ -s "$only_target" ]]; then
      printf '\nOnly in kiustore_apps:\n'
      sed -n '1,200p' "$only_target"
    fi
    if [[ -s "$different" ]]; then
      printf '\nDifferent content:\n'
      sed -n '1,200p' "$different"
    fi
    rm -rf "$tmp_dir"
    return 1
  fi

  rm -rf "$tmp_dir"
  printf '\nVerification passed: kiustore_apps mirrors application.\n'
}

case "${1:-}" in
  "")
    rsync -a --delete --itemize-changes "$SOURCE_DIR" "$TARGET_DIR"
    ;;
  --dry-run)
    rsync -a --delete --dry-run --itemize-changes "$SOURCE_DIR" "$TARGET_DIR"
    ;;
  --verify)
    verify
    ;;
  -h|--help)
    usage
    ;;
  *)
    usage
    exit 2
    ;;
esac
