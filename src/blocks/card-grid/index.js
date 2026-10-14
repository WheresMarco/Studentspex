import { __ } from '@wordpress/i18n';
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { Fields, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( {
		className: `grid grid--${ attributes.columns }`,
	} );
	const inner = useInnerBlocksProps( blockProps, {
		allowedBlocks: [
			'studentspex/card',
			'studentspex/notice',
			'studentspex/direction',
		],
		template: [
			[ 'studentspex/card' ],
			[ 'studentspex/card' ],
			[ 'studentspex/card' ],
		],
	} );
	return (
		<>
			<Fields
				attributes={ attributes }
				setAttributes={ setAttributes }
				fields={ [
					{
						key: 'columns',
						label: __( 'Kolumner', 'studentspex' ),
						type: 'range',
						min: 2,
						max: 4,
					},
				] }
			/>
			<div { ...inner } />
		</>
	);
}

register( metadata, Edit, { inner: true } );
