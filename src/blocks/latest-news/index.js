import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Fields, Preview, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	return (
		<>
			<Fields
				attributes={ attributes }
				setAttributes={ setAttributes }
				fields={ [
					{
						key: 'count',
						label: __( 'Antal nyheter', 'studentspex' ),
						type: 'range',
						min: 1,
						max: 9,
					},
				] }
			/>
			<div { ...useBlockProps() }>
				<Preview name={ metadata.name } attributes={ attributes } />
			</div>
		</>
	);
}

register( metadata, Edit );
