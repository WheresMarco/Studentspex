import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Text, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const p = { attributes, setAttributes };
	return (
		<div { ...useBlockProps( { className: 'stat' } ) }>
			<Text
				{ ...p }
				attr="num"
				tag="span"
				className="stat__num"
				plain
				placeholder="1981"
			/>
			<Text
				{ ...p }
				attr="label"
				tag="span"
				className="stat__label"
				plain
				placeholder={ __( 'Beskrivning', 'studentspex' ) }
			/>
		</div>
	);
}

register( metadata, Edit );
