// Writes src/blocks/<name>/block.json from tools/blocks.spec.mjs.
import { mkdirSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import spec from './blocks.spec.mjs';

const root = join( dirname( fileURLToPath( import.meta.url ) ), '..', 'src', 'blocks' );
for ( const [ name, def ] of Object.entries( spec ) ) {
	const json = {
		$schema: 'https://schemas.wp.org/trunk/block.json',
		apiVersion: 3,
		name: `studentspex/${ name }`,
		title: def.title,
		category: 'studentspex',
		icon: def.icon,
		description: def.description || '',
		textdomain: 'studentspex',
		...( def.parent ? { parent: def.parent } : {} ),
		...( def.usesContext ? { usesContext: def.usesContext } : {} ),
		attributes: def.attributes,
		supports: { html: false, anchor: ! def.parent, ...( def.supports || {} ) },
		editorScript: 'file:./index.js',
		render: 'file:./render.php',
	};
	mkdirSync( join( root, name ), { recursive: true } );
	writeFileSync( join( root, name, 'block.json' ), JSON.stringify( json, null, '\t' ) + '\n' );
}
console.log( `wrote ${ Object.keys( spec ).length } block.json files` );
