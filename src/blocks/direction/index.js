import { __ } from '@wordpress/i18n';
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { Fields, Text, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const p = { attributes, setAttributes };
	const inner = useInnerBlocksProps(
		{ className: 'direction__people' },
		{
			allowedBlocks: [ 'studentspex/person' ],
			template: [
				[ 'studentspex/person', { role: 'Directeur' } ],
				[ 'studentspex/person', { role: 'Ekonomichef' } ],
				[ 'studentspex/person', { role: 'Producent' } ],
			],
		}
	);
	return (
		<>
			<Fields
				attributes={ attributes }
				setAttributes={ setAttributes }
				fields={ [
					{
						key: 'email',
						label: __( 'Gruppens e-postadress', 'studentspex' ),
						type: 'email',
					},
				] }
			/>
			<div
				{ ...useBlockProps( {
					className: 'direction card card--dark',
				} ) }
			>
				<h3>
					{ __( 'Direction', 'studentspex' ) }{ ' ' }
					<Text
						{ ...p }
						attr="year"
						tag="span"
						className="year"
						plain
						placeholder="2027"
					/>
				</h3>
				{ attributes.email && (
					<p className="direction__email">{ attributes.email }</p>
				) }
				<div { ...inner } />
			</div>
		</>
	);
}

register( metadata, Edit, { inner: true } );
