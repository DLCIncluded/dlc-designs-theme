#!/usr/bin/env python3
"""Bump the theme version across all metadata files and zip a release build."""

import argparse
import re
import sys
import time
import zipfile
from pathlib import Path

THEME_DIR = Path(__file__).resolve().parent

VERSION_RE = re.compile(r'^\d+\.\d+\.\d+$')

EXCLUDE_DIRS = {
	'node_modules', '.git', '.github', 'vendor', 'sass',
	'.claude', 'recovered files', 'releases', '__pycache__',
}
EXCLUDE_FILES = {
	'.DS_Store', '.stylelintrc.json', '.eslintrc', '.gitattributes',
	'.gitignore', 'README.md', 'composer.json', 'composer.lock',
	'package-lock.json', 'package.json', '.travis.yml', 'phpcs.xml.dist',
	'style.css.map', 'woocommerce.css.map', 'yarn.lock', 'release.py',
}


def prompt_version():
	while True:
		version = input('Enter new version (e.g. 1.2.0): ').strip()
		if VERSION_RE.match(version):
			return version
		print('Version must look like X.Y.Z (e.g. 1.2.0) — try again.')


def bump_file(path, pattern, replacement, label, required=True):
	text = path.read_text(encoding='utf-8')
	new_text, count = pattern.subn(replacement, text, count=1)
	if count == 0:
		if required:
			raise RuntimeError(f'Could not find {label} in {path.name} — file may have changed format.')
		print(f'  skipped {path.name} (no {label} present)')
		return
	path.write_text(new_text, encoding='utf-8')
	print(f'  updated {label} in {path.name}')


def bump_version(version):
	bump_file(
		THEME_DIR / 'style.css',
		re.compile(r'(Version:\s*)\d+\.\d+\.\d+'),
		rf'\g<1>{version}',
		'Version header',
	)
	# sass/style.scss carries its own copy of this header (it's the source
	# style.css is compiled from) — without this, the next `compile:css`
	# regenerates style.css from the stale SCSS version and silently
	# reverts the bump above.
	bump_file(
		THEME_DIR / 'sass' / 'style.scss',
		re.compile(r'(Version:\s*)\d+\.\d+\.\d+'),
		rf'\g<1>{version}',
		'Version header',
	)
	bump_file(
		THEME_DIR / 'readme.txt',
		re.compile(r'(Stable tag:\s*)\d+\.\d+\.\d+'),
		rf'\g<1>{version}',
		'Stable tag',
	)
	bump_file(
		THEME_DIR / 'functions.php',
		re.compile(r"(define\(\s*'DLC_DESIGNS_VERSION',\s*')\d+\.\d+\.\d+(')"),
		rf'\g<1>{version}\g<2>',
		'DLC_DESIGNS_VERSION constant',
	)
	bump_file(
		THEME_DIR / 'package.json',
		re.compile(r'("version":\s*")\d+\.\d+\.\d+(")'),
		rf'\g<1>{version}\g<2>',
		'version field',
	)
	bump_file(
		THEME_DIR / 'composer.json',
		re.compile(r'("version":\s*")\d+\.\d+\.\d+(")'),
		rf'\g<1>{version}\g<2>',
		'version field',
		required=False,
	)


def build_zip(version):
	releases_dir = THEME_DIR / 'releases'
	releases_dir.mkdir(exist_ok=True)
	zip_path = releases_dir / f'{THEME_DIR.name}-{version}.zip'

	with zipfile.ZipFile(zip_path, 'w', zipfile.ZIP_DEFLATED) as zf:
		for path in THEME_DIR.rglob('*'):
			if path.is_dir():
				continue
			if any(part in EXCLUDE_DIRS for part in path.relative_to(THEME_DIR).parts[:-1]):
				continue
			if path.name in EXCLUDE_FILES:
				continue
			arcname = Path(THEME_DIR.name) / path.relative_to(THEME_DIR)

			# ZIP's DOS date format can't represent anything before 1980 —
			# a stray file with a bogus/epoch mtime (e.g. a botched output
			# redirect) would otherwise crash the whole build.
			date_time = time.localtime(path.stat().st_mtime)[:6]
			if date_time[0] < 1980:
				date_time = (1980, 1, 1, 0, 0, 0)
			zinfo = zipfile.ZipInfo(str(arcname).replace('\\', '/'), date_time)
			zinfo.compress_type = zipfile.ZIP_DEFLATED
			zf.writestr(zinfo, path.read_bytes())

	print(f'  built {zip_path.relative_to(THEME_DIR)}')
	return zip_path


def main():
	parser = argparse.ArgumentParser(description=__doc__)
	parser.add_argument('version', nargs='?', help='New version, e.g. 1.2.0')
	args = parser.parse_args()

	if args.version:
		if not VERSION_RE.match(args.version):
			sys.exit(f'Invalid version "{args.version}" — expected format X.Y.Z (e.g. 1.2.0)')
		version = args.version
	else:
		version = prompt_version()

	current = (THEME_DIR / 'style.css').read_text(encoding='utf-8')
	match = re.search(r'Version:\s*(\d+\.\d+\.\d+)', current)
	current_version = match.group(1) if match else 'unknown'

	print(f'\n{THEME_DIR.name}: {current_version} -> {version}')
	if input('Proceed? [y/N] ').strip().lower() != 'y':
		sys.exit('Aborted.')

	print('\nUpdating version strings...')
	bump_version(version)

	print('\nBuilding release zip...')
	zip_path = build_zip(version)

	print(f'\nDone. Release package: {zip_path}')


if __name__ == '__main__':
	main()
