import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Fields, Text, register, editorData } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const p = { attributes, setAttributes };
	const logo =
		attributes.imageUrl ||
		`${ editorData().themeUrl }/assets/img/lysator.svg`;
	return (
		<>
			<Fields
				attributes={ attributes }
				setAttributes={ setAttributes }
				fields={ [
					{
						key: 'variant',
						label: __( 'Utseende', 'studentspex' ),
						type: 'select',
						options: [
							{
								label: __( 'Kort', 'studentspex' ),
								value: 'card',
							},
							{
								label: __( 'Smal rad', 'studentspex' ),
								value: 'slim',
							},
						],
					},
					{
						key: 'url',
						label: __( 'Länk', 'studentspex' ),
						type: 'url',
					},
					{
						key: 'imageUrl',
						idKey: 'imageId',
						label: __(
							'Logotyp (tomt = Lysators logotyp)',
							'studentspex'
						),
						type: 'image',
					},
				] }
			/>
			<div
				{ ...useBlockProps( {
					className:
						attributes.variant === 'slim'
							? 'partner partner--slim'
							: 'container partner',
				} ) }
			>
				<span className="partner__link">
					<img className="partner__logo" src={ logo } alt="" />
					<span className="partner__text">
						<Text
							{ ...p }
							attr="intro"
							tag="span"
							className="partner__intro"
							plain
						/>
						<Text
							{ ...p }
							attr="name"
							tag="span"
							className="partner__name"
							plain
						/>
					</span>
				</span>
			</div>
		</>
	);
}

register( metadata, Edit );
