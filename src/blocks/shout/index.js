import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Fields, Text, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes, isSelected } ) {
	const p = { attributes, setAttributes };
	const blockProps = useBlockProps( { className: 'shout-edit' } );
	return (
		<>
			<Fields
				attributes={ attributes }
				setAttributes={ setAttributes }
				fields={ [
					{ key: 'icon', label: __( 'Emoji', 'studentspex' ) },
				] }
			/>
			<div { ...blockProps }>
				<Text
					{ ...p }
					attr="label"
					tag="span"
					className="shout"
					plain
					placeholder={ __( 'Tillrop!', 'studentspex' ) }
				/>
				{ isSelected && (
					<div className="shout-edit__result">
						<span aria-hidden="true">{ attributes.icon }</span>
						<Text
							{ ...p }
							attr="result"
							tag="span"
							plain
							placeholder={ __(
								'Vad händer på scen?',
								'studentspex'
							) }
						/>
					</div>
				) }
			</div>
		</>
	);
}

register( metadata, Edit );
