import { __ } from '@wordpress/i18n';
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import {
	Fields,
	Text,
	register,
	editorData,
	Spotlights,
	Scallop,
	spotlightOptions,
} from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const { source, imageUrl, imageWide, spotlights, scallopColor, level } =
		attributes;
	const show = editorData().currentShow;
	const fromShow = source === 'show';
	const blockProps = useBlockProps( { className: 'stage page-hero' } );
	const inner = useInnerBlocksProps(
		{ className: 'page-hero__actions' },
		{ allowedBlocks: [ 'core/buttons' ] }
	);
	const p = { attributes, setAttributes };
	const H = `h${ level }`;
	return (
		<>
			<Fields
				attributes={ attributes }
				setAttributes={ setAttributes }
				fields={ [
					{
						key: 'source',
						label: __( 'Rubrik', 'studentspex' ),
						type: 'select',
						options: [
							{
								label: __( 'Egen text', 'studentspex' ),
								value: 'custom',
							},
							{
								label: __(
									'Aktuell uppsättnings titel',
									'studentspex'
								),
								value: 'show',
							},
						],
					},
					{
						key: 'imageUrl',
						idKey: 'imageId',
						label: __( 'Bild (valfri)', 'studentspex' ),
						type: 'image',
					},
					{
						key: 'imageWide',
						label: __( 'Bred bild (t.ex. Lisse)', 'studentspex' ),
						type: 'toggle',
					},
					{
						key: 'spotlights',
						label: __( 'Strålkastare', 'studentspex' ),
						type: 'select',
						options: spotlightOptions,
					},
					{
						key: 'scallopColor',
						label: __( 'Ridåkantens färg', 'studentspex' ),
						type: 'select',
						options: [
							{
								label: __( 'Grädde', 'studentspex' ),
								value: 'cream',
							},
							{
								label: __( 'Vit', 'studentspex' ),
								value: 'paper',
							},
						],
					},
				] }
			/>
			<section { ...blockProps }>
				<Spotlights which={ spotlights } />
				<div className="container page-hero__grid">
					<div>
						<Text
							{ ...p }
							attr="eyebrow"
							className="eyebrow"
							plain
							placeholder={ __( 'Överrubrik', 'studentspex' ) }
						/>
						{ fromShow ? (
							<H>
								{ show
									? show.title
									: __(
											'(aktuell uppsättning)',
											'studentspex'
									  ) }
							</H>
						) : (
							<Text
								{ ...p }
								attr="title"
								tag={ H }
								formats={ [ 'core/italic' ] }
								placeholder={ __(
									'Sidans rubrik',
									'studentspex'
								) }
							/>
						) }
						{ fromShow && show && show.subtitle && (
							<p className="hero__sub">{ show.subtitle }</p>
						) }
						<Text
							{ ...p }
							attr="lead"
							className="lead"
							placeholder={ __( 'Ingress', 'studentspex' ) }
						/>
						<div { ...inner } />
					</div>
					{ imageUrl && (
						<img
							className={ `page-hero__art${
								imageWide ? ' page-hero__art--wide' : ''
							}` }
							src={ imageUrl }
							alt=""
						/>
					) }
				</div>
			</section>
			<Scallop color={ scallopColor } />
		</>
	);
}

register( metadata, Edit, { inner: true } );
