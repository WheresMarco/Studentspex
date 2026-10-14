import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Text, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const p = { attributes, setAttributes };
	return (
		<li { ...useBlockProps() }>
			<Text
				{ ...p }
				attr="year"
				tag="span"
				className="history__year"
				plain
				placeholder="1979"
			/>
			<Text
				{ ...p }
				attr="title"
				tag="h3"
				plain
				placeholder={ __( 'Händelse', 'studentspex' ) }
			/>
			<Text
				{ ...p }
				attr="text"
				placeholder={ __( 'Beskrivning', 'studentspex' ) }
			/>
		</li>
	);
}

register( metadata, Edit );
