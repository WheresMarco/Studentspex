import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Fields, Preview, register, editorData } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	return (
		<>
			<Fields
				attributes={ attributes }
				setAttributes={ setAttributes }
				fields={ [
					{
						key: 'buttonText',
						label: __( 'Knapptext', 'studentspex' ),
					},
					{
						key: 'fallbackTitle',
						label: __(
							'Rubrik om ingen egen rubrik finns',
							'studentspex'
						),
					},
					{
						key: 'fallbackText',
						label: __(
							'Text om ingen egen text finns',
							'studentspex'
						),
					},
					{
						key: 'newsletterText',
						label: __(
							'Länktext till nyhetsbrevet',
							'studentspex'
						),
					},
					{
						key: 'newsletterUrl',
						label: __( 'Länk till nyhetsbrevet', 'studentspex' ),
					},
				] }
			>
				<p>
					{ __(
						'Innehållet hämtas från aktuell uppsättning. Redigera den under Uppsättningar.',
						'studentspex'
					) }{ ' ' }
					<a href={ editorData().settingsUrl }>
						{ __( 'Välj aktuell', 'studentspex' ) }
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
