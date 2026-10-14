import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Text, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const p = { attributes, setAttributes };
	return (
		<dl { ...useBlockProps( { className: 'term' } ) }>
			<dt>
				<Text
					{ ...p }
					attr="term"
					tag="span"
					plain
					placeholder={ __( 'Ord', 'studentspex' ) }
				/>
				<Text
					{ ...p }
					attr="kind"
					tag="small"
					plain
					placeholder={ __( 'Ordklass', 'studentspex' ) }
				/>
			</dt>
			<Text
				{ ...p }
				attr="definition"
				tag="dd"
				placeholder={ __( 'Förklaring', 'studentspex' ) }
			/>
		</dl>
	);
}

register( metadata, Edit );
