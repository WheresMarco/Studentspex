import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { Text, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const inner = useInnerBlocksProps(
		{ className: 'footer-bottom__aside' },
		{ allowedBlocks: [ 'studentspex/partner' ], renderAppender: false }
	);
	return (
		<div { ...useBlockProps( { className: 'container footer-bottom' } ) }>
			<Text
				attributes={ attributes }
				setAttributes={ setAttributes }
				attr="text"
				plain
			/>
			<div { ...inner } />
		</div>
	);
}

register( metadata, Edit, { inner: true } );
