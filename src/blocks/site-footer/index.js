import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { register } from '../../shared/editor';
import metadata from './block.json';

function Edit() {
	const inner = useInnerBlocksProps(
		useBlockProps( { className: 'site-footer' } )
	);
	return <footer { ...inner } />;
}

register( metadata, Edit, { inner: true } );
