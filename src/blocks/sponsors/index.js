import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { Text, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const inner = useInnerBlocksProps(
		{ className: 'sponsors__list' },
		{
			allowedBlocks: [ 'studentspex/sponsor' ],
			orientation: 'horizontal',
			template: [ [ 'studentspex/sponsor' ] ],
		}
	);
	return (
		<section { ...useBlockProps( { className: 'sponsors' } ) }>
			<div className="container">
				<Text
					attributes={ attributes }
					setAttributes={ setAttributes }
					attr="title"
					tag="h2"
					className="sponsors__title"
					plain
				/>
				<div { ...inner } />
			</div>
		</section>
	);
}

register( metadata, Edit, { inner: true } );
