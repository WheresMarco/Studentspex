import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Fields, Preview, register, editorData } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const tiles = attributes.variant === 'tiles';
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
								label: __( 'Stora rutor', 'studentspex' ),
								value: 'tiles',
							},
							{
								label: __( 'Små knappar', 'studentspex' ),
								value: 'mini',
							},
						],
					},
					...( tiles
						? [
								{
									key: 'igText',
									label: __(
										'Text, Instagram',
										'studentspex'
									),
								},
								{
									key: 'fbText',
									label: __(
										'Text, Facebook',
										'studentspex'
									),
								},
								{
									key: 'ttText',
									label: __( 'Text, TikTok', 'studentspex' ),
								},
						  ]
						: [] ),
				] }
			>
				<p>
					<a href={ editorData().settingsUrl }>
						{ __(
							'Ändra länkarna under Spexet → Inställningar',
							'studentspex'
						) }
					</a>
				</p>
			</Fields>
			<div { ...useBlockProps() }>
				<Preview name={ metadata.name } attributes={ attributes } />
			</div>
		</>
	);
}

register( metadata, Edit );
