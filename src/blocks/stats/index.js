import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { register } from '../../shared/editor';
import metadata from './block.json';

function Edit() {
	const blockProps = useBlockProps( { className: 'stats' } );
	const inner = useInnerBlocksProps( blockProps, {
		allowedBlocks: [ 'studentspex/stat' ],
		orientation: 'horizontal',
		template: [
			[ 'studentspex/stat' ],
			[ 'studentspex/stat' ],
			[ 'studentspex/stat' ],
			[ 'studentspex/stat' ],
		],
	} );
	return <div { ...inner } />;
}

register( metadata, Edit, { inner: true } );
