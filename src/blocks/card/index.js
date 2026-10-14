import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Fields, Text, register } from '../../shared/editor';
import metadata from './block.json';

export function cardClass( { variant, hover, label, email } ) {
	return [
		'card',
		variant !== 'default' && `card--${ variant }`,
		hover && 'group-card',
		( label || email ) && 'contact-card',
	]
		.filter( Boolean )
		.join( ' ' );
}

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
								label: __( 'Vit', 'studentspex' ),
								value: 'default',
							},
							{
								label: __(
									'Mörk (på mörk bakgrund)',
									'studentspex'
								),
								value: 'dark',
							},
							{
								label: __( 'Guld', 'studentspex' ),
								value: 'gold',
							},
						],
					},
					{
						key: 'hover',
						label: __( 'Lyft vid hovring', 'studentspex' ),
						type: 'toggle',
					},
					{
						key: 'email',
						label: __( 'E-postadress (valfri)', 'studentspex' ),
						type: 'email',
						help: __( 'Visas skyddad mot spam.', 'studentspex' ),
					},
				] }
			/>
			<article
				{ ...useBlockProps( { className: cardClass( attributes ) } ) }
			>
				{ attributes.icon && (
					<div className="card__icon" aria-hidden="true">
						{ attributes.icon }
					</div>
				) }
				<Text
					{ ...p }
					attr="label"
					tag="span"
					className="contact-card__role"
					plain
					placeholder={ __( 'Etikett (valfri)', 'studentspex' ) }
				/>
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
				{ attributes.email && (
					<a className="mail" href={ `mailto:${ attributes.email }` }>
						{ attributes.email }
					</a>
				) }
			</article>
		</>
	);
}

register( metadata, Edit );
