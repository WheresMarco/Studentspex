// Builds ../dist/studentspex.zip: the installable theme (compiled build/, no sources or node_modules).
// The folder inside the zip is always "studentspex", whatever this directory is called.
import { execFileSync } from 'node:child_process';
import { cpSync, existsSync, mkdirSync, mkdtempSync, rmSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const SLUG = 'studentspex';
const theme = join( dirname( fileURLToPath( import.meta.url ) ), '..' );
const distDir = join( theme, '..', 'dist' );
const out = join( distDir, `${ SLUG }.zip` );
const include = [
	'style.css',
	'theme.json',
	'functions.php',
	'screenshot.png',
	'README.md',
	'inc',
	'templates',
	'parts',
	'patterns',
	'assets',
	'build',
	'data',
	'languages',
];

if ( ! existsSync( join( theme, 'build', 'blocks' ) ) ) {
	console.error( 'build/ missing – run `npm run build` first.' );
	process.exit( 1 );
}

const stage = mkdtempSync( join( tmpdir(), 'spex-zip-' ) );
for ( const p of include ) {
	cpSync( join( theme, p ), join( stage, SLUG, p ), { recursive: true } );
}
mkdirSync( distDir, { recursive: true } );
rmSync( out, { force: true } );
execFileSync( 'zip', [ '-rq9', out, SLUG, '-x', '*.DS_Store' ], { cwd: stage, stdio: 'inherit' } );
rmSync( stage, { recursive: true, force: true } );
console.log( `wrote ${ out }` );
