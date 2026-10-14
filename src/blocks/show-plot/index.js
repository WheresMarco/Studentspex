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
						key: 'eyebrow',
						label: __( 'Överrubrik', 'studentspex' ),
					},
					{ key: 'title', label: __( 'Rubrik', 'studentspex' ) },
					{
						key: 'outro',
						label: __( 'Avslutande text', 'studentspex' ),
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
