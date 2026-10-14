import { __ } from '@wordpress/i18n';
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { Fields, Text, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const { align, level } = attributes;
	const blockProps = useBlockProps( {
		className: `section-head section-head--split${
			align === 'center' ? ' section-head--center' : ''
		}`,
	} );
	const inner = useInnerBlocksProps(
		{ className: 'section-head__actions' },
		{ allowedBlocks: [ 'core/buttons' ], renderAppender: false }
	);
	const p = { attributes, setAttributes };
	return (
		<>
			<Fields
				attributes={ attributes }
				setAttributes={ setAttributes }
				fields={ [
					{
						key: 'align',
						label: __( 'Justering', 'studentspex' ),
						type: 'select',
						options: [
							{
								label: __( 'Vänster', 'studentspex' ),
								value: 'left',
							},
							{
								label: __( 'Centrerad', 'studentspex' ),
								value: 'center',
							},
						],
					},
					{
						key: 'level',
						label: __( 'Rubriknivå', 'studentspex' ),
						type: 'select',
						options: [
							{ label: 'H1', value: 1 },
							{ label: 'H2', value: 2 },
							{ label: 'H3', value: 3 },
						],
					},
				] }
			>
				<p>
					{ __(
						'Lägg till en Knappar-grupp i blocket för att visa en knapp till höger.',
						'studentspex'
					) }
				</p>
			</Fields>
			<div { ...blockProps }>
				<div>
					<Text
						{ ...p }
						attr="eyebrow"
						className="eyebrow"
						placeholder={ __( 'Överrubrik', 'studentspex' ) }
						plain
					/>
					<Text
						{ ...p }
						attr="title"
						tag={ `h${ level }` }
						placeholder={ __( 'Rubrik', 'studentspex' ) }
						formats={ [ 'core/italic' ] }
					/>
					<Text
						{ ...p }
						attr="lead"
						className="lead"
						placeholder={ __( 'Ingress (valfri)', 'studentspex' ) }
					/>
				</div>
				<div { ...inner } />
			</div>
		</>
	);
}

register( metadata, Edit, { inner: true } );
