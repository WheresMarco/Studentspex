import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Fields, Text, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const p = { attributes, setAttributes };
	return (
		<>
			<Fields
				attributes={ attributes }
				setAttributes={ setAttributes }
				fields={ [
					{ key: 'icon', label: __( 'Ikon (emoji)', 'studentspex' ) },
					{
						key: 'variant',
						label: __( 'Färg', 'studentspex' ),
						type: 'select',
						options: [
							{
								label: __( 'Blå', 'studentspex' ),
								value: 'blue',
							},
							{
								label: __( 'Guld', 'studentspex' ),
								value: 'gold',
							},
						],
					},
				] }
			/>
			<div
				{ ...useBlockProps( {
					className: `notice${
						attributes.variant === 'gold' ? ' notice--gold' : ''
					}`,
				} ) }
			>
				<span className="notice__icon" aria-hidden="true">
					{ attributes.icon }
				</span>
				<div>
					<Text
						{ ...p }
						attr="title"
						tag="h3"
						plain
						placeholder={ __( 'Rubrik', 'studentspex' ) }
					/>
					<Text
						{ ...p }
						attr="text"
						placeholder={ __( 'Text', 'studentspex' ) }
					/>
				</div>
			</div>
		</>
	);
}

register( metadata, Edit );
