import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { register } from '../../shared/editor';
import metadata from './block.json';

function Edit() {
	const inner = useInnerBlocksProps(
		useBlockProps( { className: 'steps' } ),
		{
			allowedBlocks: [ 'studentspex/step' ],
			orientation: 'horizontal',
			template: [
				[ 'studentspex/step' ],
				[ 'studentspex/step' ],
				[ 'studentspex/step' ],
				[ 'studentspex/step' ],
			],
		}
	);
	return <ol { ...inner } />;
}

register( metadata, Edit, { inner: true } );
