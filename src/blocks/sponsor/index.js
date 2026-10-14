import { __ } from '@wordpress/i18n';
import { useBlockProps, MediaPlaceholder } from '@wordpress/block-editor';
import { Fields, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const { imageUrl, alt } = attributes;
	const blockProps = useBlockProps( { className: 'sponsor' } );
	return (
		<>
			<Fields
				attributes={ attributes }
				setAttributes={ setAttributes }
				fields={ [
					{
						key: 'imageUrl',
						idKey: 'imageId',
						altKey: 'alt',
						label: __( 'Logotyp', 'studentspex' ),
						type: 'image',
					},
					{
						key: 'alt',
						label: __( 'Sponsorns namn (alt-text)', 'studentspex' ),
					},
					{
						key: 'link',
						label: __( 'Länk (valfri)', 'studentspex' ),
						type: 'url',
					},
				] }
			/>
			<div { ...blockProps }>
				{ imageUrl ? (
					<img src={ imageUrl } alt={ alt } />
				) : (
					<MediaPlaceholder
						icon="format-image"
						labels={ {
							title: __( 'Sponsorlogotyp', 'studentspex' ),
						} }
						accept="image/*"
						allowedTypes={ [ 'image' ] }
						onSelect={ ( m ) =>
							setAttributes( {
								imageId: m.id,
								imageUrl: m.url,
								alt: m.alt || alt,
							} )
						}
					/>
				) }
			</div>
		</>
	);
}

register( metadata, Edit );
