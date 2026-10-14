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
						key: 'datesTitle',
						label: __( 'Rubrik, datum', 'studentspex' ),
					},
					{
						key: 'timesTitle',
						label: __( 'Tider – rubrik', 'studentspex' ),
					},
					{
						key: 'timesText',
						label: __( 'Tider – text', 'studentspex' ),
					},
					{
						key: 'groupsTitle',
						label: __( 'Grupper – rubrik', 'studentspex' ),
					},
					{
						key: 'groupsText',
						label: __(
							'Grupper – text (följs av biljettmejlen)',
							'studentspex'
						),
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
