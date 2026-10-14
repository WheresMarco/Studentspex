/*
 * Shared editor helpers for Studentspex blocks.
 *
 * Blocks render the same markup and classes in the editor as on the front end
 * (the theme stylesheet is loaded as an editor style), with RichText for every
 * visible text so editors can change copy directly on the page.
 */
import {
	RichText,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	useBlockProps,
	useInnerBlocksProps,
	InnerBlocks,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	ToggleControl,
	SelectControl,
	RangeControl,
	Button,
} from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

export const editorData = () => window.studentspexEditor || {};

/*
 * Inline-editable text bound to one attribute.
 * @param root0
 * @param root0.attr
 * @param root0.attributes
 * @param root0.setAttributes
 * @param root0.tag
 * @param root0.className
 * @param root0.placeholder
 * @param root0.plain
 * @param root0.formats
 */
export function Text( {
	attr,
	attributes,
	setAttributes,
	tag = 'p',
	className,
	placeholder,
	plain = false,
	formats,
} ) {
	return (
		<RichText
			tagName={ tag }
			className={ className }
			value={ attributes[ attr ] }
			onChange={ ( v ) => setAttributes( { [ attr ]: v } ) }
			placeholder={ placeholder }
			allowedFormats={
				plain
					? []
					: formats || [ 'core/bold', 'core/italic', 'core/link' ]
			}
			withoutInteractiveFormatting={ plain }
		/>
	);
}

/*
 * Sidebar panel generated from a field list.
 *
 * Field: { key, label, type: 'text'|'url'|'email'|'toggle'|'select'|'range'|'image', options, min, max, help }
 * @param root0
 * @param root0.title
 * @param root0.fields
 * @param root0.attributes
 * @param root0.setAttributes
 * @param root0.children
 */
export function Fields( {
	title = __( 'Inställningar', 'studentspex' ),
	fields,
	attributes,
	setAttributes,
	children,
} ) {
	return (
		<InspectorControls>
			<PanelBody title={ title } initialOpen>
				{ fields.map( ( f ) => {
					const set = ( v ) => setAttributes( { [ f.key ]: v } );
					const common = {
						key: f.key,
						label: f.label,
						help: f.help,
						__nextHasNoMarginBottom: true,
					};
					switch ( f.type ) {
						case 'toggle':
							return (
								<ToggleControl
									{ ...common }
									checked={ !! attributes[ f.key ] }
									onChange={ set }
								/>
							);
						case 'select':
							return (
								<SelectControl
									{ ...common }
									value={ attributes[ f.key ] }
									options={ f.options }
									onChange={ ( v ) =>
										set(
											typeof attributes[ f.key ] ===
												'number'
												? Number( v )
												: v
										)
									}
									__next40pxDefaultSize
								/>
							);
						case 'range':
							return (
								<RangeControl
									{ ...common }
									value={ attributes[ f.key ] }
									min={ f.min }
									max={ f.max }
									onChange={ set }
									__next40pxDefaultSize
								/>
							);
						case 'image':
							return (
								<ImageField
									key={ f.key }
									label={ f.label }
									id={ attributes[ f.idKey ] }
									url={ attributes[ f.key ] }
									onChange={ ( media ) =>
										setAttributes( {
											[ f.idKey ]: media ? media.id : 0,
											[ f.key ]: media ? media.url : '',
											...( f.altKey && media
												? {
														[ f.altKey ]:
															media.alt ||
															attributes[
																f.altKey
															],
												  }
												: {} ),
										} )
									}
								/>
							);
						default:
							return (
								<TextControl
									{ ...common }
									type={ f.type || 'text' }
									value={ attributes[ f.key ] || '' }
									onChange={ set }
									__next40pxDefaultSize
								/>
							);
					}
				} ) }
				{ children }
			</PanelBody>
		</InspectorControls>
	);
}

/*
 * Media picker with preview and remove button.
 * @param root0
 * @param root0.label
 * @param root0.id
 * @param root0.url
 * @param root0.onChange
 */
export function ImageField( { label, id, url, onChange } ) {
	return (
		<div className="studentspex-image-field" style={ { marginBottom: 16 } }>
			<p style={ { fontWeight: 600, margin: '0 0 8px' } }>{ label }</p>
			{ url && (
				<img
					src={ url }
					alt=""
					style={ {
						maxWidth: '100%',
						maxHeight: 140,
						display: 'block',
						marginBottom: 8,
						background: '#0e1f52',
						borderRadius: 6,
					} }
				/>
			) }
			<MediaUploadCheck>
				<MediaUpload
					allowedTypes={ [ 'image' ] }
					value={ id }
					onSelect={ onChange }
					render={ ( { open } ) => (
						<Button variant="secondary" onClick={ open }>
							{ url
								? __( 'Byt bild', 'studentspex' )
								: __( 'Välj bild', 'studentspex' ) }
						</Button>
					) }
				/>
			</MediaUploadCheck>
			{ url && (
				<Button
					variant="link"
					isDestructive
					onClick={ () => onChange( null ) }
					style={ { marginLeft: 8 } }
				>
					{ __( 'Ta bort', 'studentspex' ) }
				</Button>
			) }
		</div>
	);
}

/*
 * Preview rendered by render.php (for blocks that read live data).
 * @param root0
 * @param root0.name
 * @param root0.attributes
 */
export function Preview( { name, attributes } ) {
	return (
		<ServerSideRender
			block={ name }
			attributes={ attributes }
			skipBlockSupportAttributes
			LoadingResponsePlaceholder={ () => (
				<p style={ { padding: 24, opacity: 0.6 } }>
					{ __( 'Laddar förhandsvisning…', 'studentspex' ) }
				</p>
			) }
		/>
	);
}

/* Small inline SVG icons matching inc/helpers.php. */
const stroke = {
	fill: 'none',
	stroke: 'currentColor',
	strokeWidth: 2,
	strokeLinecap: 'round',
	strokeLinejoin: 'round',
};
export const Icon = {
	ticket: () => (
		<svg viewBox="0 0 24 24" { ...stroke } aria-hidden="true">
			<path d="M3 9a3 3 0 0 0 0 6v3a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-3a3 3 0 0 1 0-6V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1z" />
			<path d="M14 5v2M14 11v2M14 17v2" />
		</svg>
	),
	calendar: () => (
		<svg viewBox="0 0 24 24" { ...stroke } aria-hidden="true">
			<rect x="3" y="5" width="18" height="16" rx="2" />
			<path d="M16 3v4M8 3v4M3 10h18" />
		</svg>
	),
	pin: () => (
		<svg viewBox="0 0 24 24" { ...stroke } aria-hidden="true">
			<path d="M12 21s-7-6.2-7-11a7 7 0 1 1 14 0c0 4.8-7 11-7 11z" />
			<circle cx="12" cy="10" r="2.5" />
		</svg>
	),
	arrow: () => (
		<svg viewBox="0 0 24 24" { ...stroke } aria-hidden="true">
			<path d="M5 12h14M13 6l6 6-6 6" />
		</svg>
	),
};

/*
 * Wrapper props + inner blocks props in one call.
 * @param className
 * @param innerClassName
 * @param innerOptions
 */
export function useWrapper( className, innerClassName, innerOptions = {} ) {
	const blockProps = useBlockProps( { className } );
	const inner = useInnerBlocksProps(
		innerClassName ? { className: innerClassName } : {},
		innerOptions
	);
	return { blockProps, inner };
}

/*
 * Register a block: dynamic render on the server, so save() only stores inner blocks.
 * @param metadata
 * @param edit
 * @param root0
 * @param root0.inner
 */
export function register( metadata, edit, { inner = false } = {} ) {
	registerBlockType( metadata.name, {
		...metadata,
		edit,
		save: inner ? () => <InnerBlocks.Content /> : () => null,
	} );
}

export const spotlightOptions = [
	{ label: __( 'Inga', 'studentspex' ), value: 'none' },
	{ label: __( 'Vänster', 'studentspex' ), value: 'left' },
	{ label: __( 'Höger', 'studentspex' ), value: 'right' },
	{ label: __( 'Båda', 'studentspex' ), value: 'both' },
];

export function Spotlights( { which } ) {
	return (
		<>
			{ ( which === 'left' || which === 'both' ) && (
				<div className="spotlight spotlight--left" aria-hidden="true" />
			) }
			{ ( which === 'right' || which === 'both' ) && (
				<div
					className="spotlight spotlight--right"
					aria-hidden="true"
				/>
			) }
		</>
	);
}

export function Scallop( { color = 'cream' } ) {
	return (
		<svg
			className={ `scallop scallop--${ color }` }
			viewBox="0 0 1200 28"
			preserveAspectRatio="none"
			aria-hidden="true"
		>
			<path
				fill="currentColor"
				d="M0 28V14C50 14 50 0 100 0s50 14 100 14S250 0 300 0s50 14 100 14S450 0 500 0s50 14 100 14S650 0 700 0s50 14 100 14S850 0 900 0s50 14 100 14S1050 0 1100 0s50 14 100 14v14Z"
			/>
		</svg>
	);
}
