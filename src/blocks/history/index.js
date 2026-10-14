import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { register } from '../../shared/editor';
import metadata from './block.json';

function Edit() {
	const inner = useInnerBlocksProps(
		useBlockProps( { className: 'history' } ),
		{
			allowedBlocks: [ 'studentspex/history-item' ],
			template: [
				[ 'studentspex/history-item' ],
				[ 'studentspex/history-item' ],
			],
		}
	);
	return <ol { ...inner } />;
}

register( metadata, Edit, { inner: true } );
