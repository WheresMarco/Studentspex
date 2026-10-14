import { __ } from '@wordpress/i18n';
import {
	Fields,
	register,
	useWrapper,
	Spotlights,
	Scallop,
	spotlightOptions,
} from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const { variant, width, tight, spotlights, scallop, scallopColor, padTop } =
		attributes;
	const isStage = variant === 'stage';
	const cls = [
		'section',
		isStage ? 'stage section--dark' : `section--${ variant }`,
		tight && 'section--tight',
		! padTop && 'section--flush-top',
	]
		.filter( Boolean )
		.join( ' ' );
	const { blockProps, inner } = useWrapper(
		cls,
		`container${ width === 'narrow' ? ' container--narrow' : '' }`
	);
	return (
		<>
			<Fields
				title={ __( 'Sektion', 'studentspex' ) }
				attributes={ attributes }
				setAttributes={ setAttributes }
				fields={ [
					{
						key: 'variant',
						label: __( 'Bakgrund', 'studentspex' ),
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
							{
								label: __( 'Marinblå', 'studentspex' ),
								value: 'navy',
							},
							{
								label: __(
									'Ridå (diamantmönster)',
									'studentspex'
								),
								value: 'stage',
							},
						],
					},
					{
						key: 'width',
						label: __( 'Bredd', 'studentspex' ),
						type: 'select',
						options: [
							{
								label: __( 'Bred', 'studentspex' ),
								value: 'wide',
							},
							{
								label: __( 'Smal', 'studentspex' ),
								value: 'narrow',
							},
						],
					},
					{
						key: 'tight',
						label: __( 'Mindre luft', 'studentspex' ),
						type: 'toggle',
					},
					{
						key: 'padTop',
						label: __( 'Luft ovanför', 'studentspex' ),
						type: 'toggle',
					},
					...( isStage
						? [
								{
									key: 'spotlights',
									label: __( 'Strålkastare', 'studentspex' ),
									type: 'select',
									options: spotlightOptions,
								},
								{
									key: 'scallop',
									label: __(
										'Ridåkant nedtill',
										'studentspex'
									),
									type: 'toggle',
								},
						  ]
						: [] ),
					...( isStage && scallop
						? [
								{
									key: 'scallopColor',
									label: __(
										'Ridåkantens färg',
										'studentspex'
									),
									type: 'select',
									options: [
										{
											label: __(
												'Grädde',
												'studentspex'
											),
											value: 'cream',
										},
										{
											label: __( 'Vit', 'studentspex' ),
											value: 'paper',
										},
									],
								},
						  ]
						: [] ),
				] }
			/>
			<section { ...blockProps }>
				{ isStage && <Spotlights which={ spotlights } /> }
				<div { ...inner } />
			</section>
			{ isStage && scallop && <Scallop color={ scallopColor } /> }
		</>
	);
}

register( metadata, Edit, { inner: true } );
